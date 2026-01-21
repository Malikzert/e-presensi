<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PengajuanUserController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Dasar
        $request->validate([
            'jenis_pengajuan' => 'required|in:Cuti,Sakit,Izin',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'alasan' => 'required|string',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $tgl_mulai = Carbon::parse($request->tgl_mulai);
        $tgl_selesai = Carbon::parse($request->tgl_selesai);
        $hari_ini = Carbon::today();

        // 1. LOGIKA CEK TANGGAL TERBALIK (Mulai > Selesai)
        if ($tgl_mulai->gt($tgl_selesai)) {
            return back()->withInput()->with('error', 'Maaf, sepertinya Anda salah memasukkan tanggal. Tanggal mulai tidak boleh melampaui tanggal selesai.');
        }

        // 2. LOGIKA H-3 UNTUK CUTI
        if ($request->jenis_pengajuan === 'Cuti') {
            if ($hari_ini->diffInDays($tgl_mulai, false) < 3) {
                return back()->withInput()->with('error', 'Pengajuan cuti maksimal H-3 sebelum tanggal mulai.');
            }
        }

        // 3. LOGIKA WAJIB BUKTI UNTUK SAKIT
        if ($request->jenis_pengajuan === 'Sakit') {
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

         $user = auth()->user(); // Ambil objek user yang sedang login

        Pengajuan::create([
            'user_id'         => $user->id, // Pastikan mengambil ID yang valid
            'jenis_pengajuan' => $request->jenis_pengajuan,
            'tgl_mulai'       => $request->tgl_mulai,
            'tgl_selesai'     => $request->tgl_selesai,
            'alasan'          => $request->alasan,
            'bukti'           => $nama_file,
            'status'          => 'Pending',
        ]);
        return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil dikirim.');
    }
}