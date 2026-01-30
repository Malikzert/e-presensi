<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\KategoriPengajuan; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengajuanUserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            // Sekarang divalidasi berdasarkan ID yang ada di tabel kategori_pengajuans
            'kategori_pengajuan_id' => 'required|exists:kategori_pengajuans,id',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $tgl_mulai = Carbon::parse($request->tgl_mulai);
        $tgl_selesai = Carbon::parse($request->tgl_selesai);
        $hari_ini = Carbon::today();

        // Ambil data kategori untuk pengecekan logika nama (Cuti/Sakit)
        $kategori = KategoriPengajuan::find($request->kategori_pengajuan_id);
        $namaKategori = strtolower($kategori->nama_pengajuan);

        // 1. LOGIKA CEK TANGGAL TERBALIK
        if ($tgl_mulai->gt($tgl_selesai)) {
            return back()->withInput()->with('error', 'Tanggal mulai tidak boleh melampaui tanggal selesai.');
        }

        // 2. LOGIKA H-3 UNTUK CUTI
        if (str_contains($namaKategori, 'cuti') || str_contains($namaKategori, 'tukar shift')) {
            if ($hari_ini->diffInDays($tgl_mulai, false) < 3) {
                return back()->withInput()->with('error', 'Pengajuan cuti / Tukar shift minimal diajukan H-3 sebelum tanggal mulai.');
            }
        }

        // 3. LOGIKA WAJIB BUKTI UNTUK SAKIT
        if (str_contains($namaKategori, 'sakit')) {
            if (!$request->hasFile('bukti')) {
                return back()->withInput()->with('error', 'Izin sakit wajib melampirkan Surat Keterangan Dokter.');
            }
        }

        // 4. Proses Upload Bukti
        $nama_file = null;
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $nama_file);
        }

        // 5. Simpan ke Database
        $karyawan = Auth::user(); // Ini sekarang instance dari model Karyawan

        Pengajuan::create([
            'karyawan_id'           => $karyawan->id, // Menggunakan karyawan_id sesuai tabel baru
            'kategori_pengajuan_id' => $request->kategori_pengajuan_id, // Foreign Key
            'tgl_mulai'             => $request->tgl_mulai,
            'tgl_selesai'           => $request->tgl_selesai,
            'alasan'                => $request->alasan,
            'bukti'                 => $nama_file,
            'status'                => 'Pending',
        ]);

        return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dikirim.');
    }
}