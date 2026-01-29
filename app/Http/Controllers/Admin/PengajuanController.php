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
use App\Notifications\StatusPengajuanNotification;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('user');

        // Filter Pencarian Nama
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('kode_pengajuan', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
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
     * Fitur Tambah Pengajuan oleh Admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_pengajuan' => 'required', 
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        try {
            $nama_file = null;
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $nama_file = time() . '_' . $file->getClientOriginalName();
                $tujuan_upload = public_path('uploads/bukti');
                if (!File::isDirectory($tujuan_upload)) {
                    File::makeDirectory($tujuan_upload, 0777, true, true);
                }
                $file->move($tujuan_upload, $nama_file);
            }

            // LOGIKA BARU: Ambil hanya kata pertama (Cuti, Sakit, atau Izin)
            // Ini untuk menangani jika input berisi "Cuti / tukar shift"
            $inputUser = trim($request->jenis_pengajuan);
            $jenis = 'Izin'; // Default jika tidak cocok

            if (stripos($inputUser, 'Cuti') !== false) {
                $jenis = 'Cuti';
            } elseif (stripos($inputUser, 'Sakit') !== false) {
                $jenis = 'Sakit';
            } elseif (stripos($inputUser, 'Izin') !== false) {
                $jenis = 'Izin';
            }

            Pengajuan::create([
                'user_id' => $request->user_id,
                'jenis_pengajuan' => $jenis, // Sekarang hanya berisi 'Cuti', 'Sakit', atau 'Izin'
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'alasan' => $request->alasan,
                'bukti' => $nama_file,
                'status' => 'Pending',
                'kode_pengajuan' => 'PNANC-' . time(), // Sesuaikan dengan cara Anda generate kode
            ]);

            return back()->with('success', 'Pengajuan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Fitur Update Data Pengajuan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pengajuan' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $jenis_formatted = ucfirst(strtolower(trim($request->jenis_pengajuan)));
        
        $data = [
            'jenis_pengajuan' => $jenis_formatted,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
        ];

        if ($request->hasFile('bukti')) {
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

    /**
     * Fitur Update Status & Potong Kuota (Mendukung Cuti, Izin, Sakit)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak',
        ]);
        
        $pengajuan = Pengajuan::findOrFail($id);
        $user = User::find($pengajuan->user_id); 
        
        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        $mulai = Carbon::parse($pengajuan->tgl_mulai)->startOfDay();
        $selesai = Carbon::parse($pengajuan->tgl_selesai)->startOfDay();
        $durasi = $mulai->diffInDays($selesai) + 1;

        // SEKARANG SAKIT JUGA MEMOTONG KUOTA
        $jenisCek = strtolower(trim($pengajuan->jenis_pengajuan));
        $isPotongKuota = in_array($jenisCek, ['cuti', 'izin', 'sakit']);

        // LOGIKA 1: Jika status berubah menjadi Disetujui
        if ($request->status == 'Disetujui' && $pengajuan->status !== 'Disetujui') {
            if ($isPotongKuota) {
                if ($user->kuota_cuti < $durasi) {
                    return back()->with('error', "Kuota tidak cukup. Butuh: $durasi, Sisa: $user->kuota_cuti");
                }
                $user->decrement('kuota_cuti', $durasi);
            }
        }

        // LOGIKA 2: Jika dibatalkan
        if ($pengajuan->status == 'Disetujui' && ($request->status == 'Ditolak' || $request->status == 'Pending')) {
            if ($isPotongKuota) {
                $user->increment('kuota_cuti', $durasi);
            }
        }

        $pengajuan->update(['status' => $request->status]);

        if ($user->notif_status_pengajuan == 1) {
            $user->notify(new StatusPengajuanNotification($pengajuan));
        }

        $userUpdated = $user->fresh();
        return back()->with('success', "Status updated. Durasi: $durasi hari. Kuota {$userUpdated->name} sekarang: {$userUpdated->kuota_cuti}");
    }

    /**
     * Fitur Hapus & Kembalikan Kuota (Mendukung Cuti, Izin, Sakit)
     */
    public function destroy($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $jenisCek = strtolower(trim($pengajuan->jenis_pengajuan));
    
        // Jika data disetujui dihapus, kembalikan kuota (Termasuk Sakit)
        if ($pengajuan->status == 'Disetujui' && in_array($jenisCek, ['cuti', 'izin', 'sakit']) && $pengajuan->user) {
            $mulai = Carbon::parse($pengajuan->tgl_mulai)->startOfDay();
            $selesai = Carbon::parse($pengajuan->tgl_selesai)->startOfDay();
            $durasi = $mulai->diffInDays($selesai) + 1;
            $pengajuan->user->increment('kuota_cuti', $durasi);
        }

        if ($pengajuan->bukti && File::exists(public_path('uploads/bukti/' . $pengajuan->bukti))) {
            File::delete(public_path('uploads/bukti/' . $pengajuan->bukti));
        }

        $pengajuan->delete();
        return back()->with('success', 'Pengajuan berhasil dihapus.');
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