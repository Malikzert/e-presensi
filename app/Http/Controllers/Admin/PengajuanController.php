<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Karyawan; // Ubah User menjadi Karyawan
use App\Models\KategoriPengajuan; // Tambahkan ini
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
        // Relasi diganti ke 'karyawan' dan tambah 'kategori'
        $query = Pengajuan::with(['karyawan', 'kategori']);

        // Filter Pencarian Nama
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('kode_pengajuan', 'LIKE', "%{$search}%")
                ->orWhereHas('karyawan', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Data karyawan & kategori untuk dropdown
        $karyawans = Karyawan::where('is_admin', 0)->get();
        $kategoris = KategoriPengajuan::all();

        return view('admin.pengajuans', compact('pengajuans', 'karyawans', 'kategoris'));
    }

    public function store(Request $request)
    {
        // 1. Ambil data kategori untuk pengecekan slug
        $kategori = KategoriPengajuan::findOrFail($request->kategori_pengajuan_id);
        $hariIni = now()->startOfDay();
        $tglMulai = Carbon::parse($request->tgl_mulai)->startOfDay();

        // 2. Validasi Dasar
        $rules = [
            'karyawan_id' => 'required|exists:karyawans,id',
            'kategori_pengajuan_id' => 'required|exists:kategori_pengajuans,id',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];

        // 3. Logika H-3 untuk Cuti dan Tukar Shift
        if (in_array($kategori->slug, ['cuti', 'tukar-shift'])) {
            $minH3 = now()->addDays(3)->startOfDay();
            if ($tglMulai->lt($minH3)) {
                return back()->with('error', "Pengajuan {$kategori->nama_pengajuan} minimal dilakukan H-3 sebelum tanggal mulai.")->withInput();
            }
        }

        // 4. Logika Wajib Bukti untuk Sakit
        if ($kategori->slug == 'sakit') {
            if (!$request->hasFile('bukti')) {
                return back()->with('error', "Pengajuan Sakit wajib melampirkan bukti surat dokter.")->withInput();
            }
        }

        $request->validate($rules);

        try {
            // ... (kode upload file Anda tetap sama)
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

            Pengajuan::create([
                'karyawan_id' => $request->karyawan_id,
                'kategori_pengajuan_id' => $request->kategori_pengajuan_id,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_selesai' => $request->tgl_selesai,
                'alasan' => $request->alasan,
                'bukti' => $nama_file,
                'status' => 'Pending',
            ]);

            return back()->with('success', 'Pengajuan berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_pengajuan_id' => 'required|exists:kategori_pengajuans,id',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $kategori = KategoriPengajuan::findOrFail($request->kategori_pengajuan_id);
        $tglMulai = Carbon::parse($request->tgl_mulai)->startOfDay();

        // 1. Logika H-3 untuk Cuti dan Tukar Shift
        if (in_array($kategori->slug, ['cuti', 'tukar-shift'])) {
            $minH3 = now()->addDays(3)->startOfDay();
            if ($tglMulai->lt($minH3)) {
                return back()->with('error', "Update gagal: {$kategori->nama_pengajuan} minimal H-3 dari tanggal mulai.");
            }
        }

        // 2. Logika Wajib Bukti untuk Sakit
        // Cek jika kategori sakit, tapi tidak ada file baru DAN tidak ada file lama di database
        if ($kategori->slug == 'sakit') {
            if (!$request->hasFile('bukti') && empty($pengajuan->bukti)) {
                return back()->with('error', "Update gagal: Pengajuan Sakit wajib memiliki bukti surat dokter.");
            }
        }

        $data = [
            'kategori_pengajuan_id' => $request->kategori_pengajuan_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'alasan' => $request->alasan,
        ];

        // 3. Proses File Jika Ada
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
        
        $pengajuan = Pengajuan::with('kategori')->findOrFail($id);
        $user = Karyawan::find($pengajuan->karyawan_id); 
        
        if (!$user) {
            return back()->with('error', 'Karyawan tidak ditemukan.');
        }

        $mulai = Carbon::parse($pengajuan->tgl_mulai)->startOfDay();
        $selesai = Carbon::parse($pengajuan->tgl_selesai)->startOfDay();
        $durasi = $mulai->diffInDays($selesai) + 1;

        // Cek kategori melalui relasi slug
        $slugKategori = $pengajuan->kategori->slug;
        $isPotongKuota = in_array($slugKategori, ['cuti', 'izin', 'sakit', 'tukar-shift']);

        if ($request->status == 'Disetujui' && $pengajuan->status !== 'Disetujui') {
            if ($isPotongKuota) {
                if ($user->kuota_cuti < $durasi) {
                    return back()->with('error', "Kuota tidak cukup. Butuh: $durasi, Sisa: $user->kuota_cuti");
                }
                $user->decrement('kuota_cuti', $durasi);
            }
        }

        if ($pengajuan->status == 'Disetujui' && ($request->status == 'Ditolak' || $request->status == 'Pending')) {
            if ($isPotongKuota) {
                $user->increment('kuota_cuti', $durasi);
            }
        }

        $pengajuan->update(['status' => $request->status]);

        if ($user && $user->notif_status_pengajuan == 1) {
            $user->notify(new StatusPengajuanNotification($pengajuan));
        }

        $userUpdated = $user->fresh();
        return back()->with('success', "Status updated. Durasi: $durasi hari. Kuota {$userUpdated->name} sekarang: {$userUpdated->kuota_cuti}");
    }

    public function destroy($id)
    {
        $pengajuan = Pengajuan::with('kategori')->findOrFail($id);
        
        if ($pengajuan->status == 'Disetujui' && $pengajuan->karyawan) {
            $slugKategori = $pengajuan->kategori->slug;
            if (in_array($slugKategori, ['cuti', 'izin', 'sakit'])) {
                $mulai = Carbon::parse($pengajuan->tgl_mulai)->startOfDay();
                $selesai = Carbon::parse($pengajuan->tgl_selesai)->startOfDay();
                $durasi = $mulai->diffInDays($selesai) + 1;
                $pengajuan->karyawan->increment('kuota_cuti', $durasi);
            }
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