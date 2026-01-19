<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Exports\PengajuanExport;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('user');

        // Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Ambil data user untuk dropdown di modal tambah
        $users = User::where('is_admin', 0)->get();

        return view('admin.pengajuans', compact('pengajuans', 'users'));
    }

    /**
     * Fitur Tambah Pengajuan oleh Admin (CRUD - Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_pengajuan' => 'required|in:Cuti,Sakit,Izin',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Validasi 5MB
        ]);

        $nama_file = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan direktori ada
            $tujuan_upload = public_path('uploads/bukti');
            if (!File::isDirectory($tujuan_upload)) {
                File::makeDirectory($tujuan_upload, 0777, true, true);
            }
            
            $file->move($tujuan_upload, $nama_file);
        }

        Pengajuan::create([
            'user_id' => $request->user_id,
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
            'bukti' => $nama_file,
            'status' => 'Pending',
        ]);

        return back()->with('success', 'Pengajuan baru berhasil ditambahkan.');
    }

    /**
     * Fitur Update Data Pengajuan (CRUD - Update)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pengajuan' => 'required|in:Cuti,Sakit,Izin',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $data = $request->only(['jenis_pengajuan', 'tgl_mulai', 'tgl_selesai', 'alasan']);

        if ($request->hasFile('bukti')) {
            // Hapus file lama jika ada
            if ($pengajuan->bukti && File::exists(public_path('uploads/bukti/' . $pengajuan->bukti))) {
                File::delete(public_path('uploads/bukti/' . $pengajuan->bukti));
            }

            $file = $request->file('bukti');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $nama_file);
            $data['bukti'] = $nama_file;
        }
        
        $pengajuan->update($data);

        return back()->with('success', 'Data pengajuan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $user = $pengajuan->user;

        // Logika Pengurangan Kuota Cuti jika Disetujui
        if ($request->status == 'Disetujui' && $pengajuan->status !== 'Disetujui') {
            if ($pengajuan->jenis_pengajuan == 'Cuti') {
                $mulai = Carbon::parse($pengajuan->tgl_mulai);
                $selesai = Carbon::parse($pengajuan->tgl_selesai);
                $durasi = $mulai->diffInDays($selesai) + 1;

                if ($user->kuota_cuti < $durasi) {
                    return back()->with('error', 'Gagal! Kuota cuti karyawan ini sisa ' . $user->kuota_cuti . ' hari, sedangkan pengajuan ' . $durasi . ' hari.');
                }

                $user->decrement('kuota_cuti', $durasi);
            }
        }

        // Kembalikan kuota jika dibatalkan (Disetujui -> Ditolak)
        if ($request->status == 'Ditolak' && $pengajuan->status == 'Disetujui') {
            if ($pengajuan->jenis_pengajuan == 'Cuti') {
                $mulai = Carbon::parse($pengajuan->tgl_mulai);
                $selesai = Carbon::parse($pengajuan->tgl_selesai);
                $durasi = $mulai->diffInDays($selesai) + 1;
                
                $user->increment('kuota_cuti', $durasi);
            }
        }

        $pengajuan->update(['status' => $request->status]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        
        if ($pengajuan->status == 'Disetujui' && $pengajuan->jenis_pengajuan == 'Cuti') {
            $mulai = Carbon::parse($pengajuan->tgl_mulai);
            $selesai = Carbon::parse($pengajuan->tgl_selesai);
            $durasi = $mulai->diffInDays($selesai) + 1;
            $pengajuan->user->increment('kuota_cuti', $durasi);
        }

        // Hapus file fisik bukti saat data dihapus
        if ($pengajuan->bukti && File::exists(public_path('uploads/bukti/' . $pengajuan->bukti))) {
            File::delete(public_path('uploads/bukti/' . $pengajuan->bukti));
        }

        $pengajuan->delete();
        return back()->with('success', 'Pengajuan dihapus.');
    }
    public function export(Request $request) 
    {
        $request->validate([
            'bulan' => 'required|numeric|between:1,12',
            'tahun' => 'required|numeric',
        ]);

        $nama_file = 'Rekap_Pengajuan_' . $request->bulan . '_' . $request->tahun . '.xlsx';
        
        return Excel::download(new PengajuanExport($request->bulan, $request->tahun), $nama_file);
    }
}