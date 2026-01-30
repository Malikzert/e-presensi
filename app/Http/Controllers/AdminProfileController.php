<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Tambahkan ini untuk manajemen file yang lebih baik

class AdminProfileController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        return view('admin.profiladmin', [
            'title' => 'Edit Profil Admin'
        ]);
    }

    // Memproses update data
    public function update(Request $request)
    {
        // PERBAIKAN: Gunakan Auth::user(), bukan Auth::Karyawan()
        /** @var \App\Models\Karyawan $Karyawan */
        $Karyawan = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email,' . $Karyawan->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $Karyawan->name = $request->name;
        $Karyawan->email = $request->email;

        if ($request->filled('password')) {
            $Karyawan->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // PROTEKSI: Hapus foto lama jika ada dan bukan default.jpg
            if ($Karyawan->foto && $Karyawan->foto !== 'default.jpg') {
                $oldPath = public_path('images/users/' . $Karyawan->foto);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('foto');
            // Menambahkan prefix admin agar terlihat rapi di folder
            $nama_file = time() . '_admin.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/users'), $nama_file);
            
            // Simpan nama file baru ke database
            $Karyawan->foto = $nama_file;
        }

        $Karyawan->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}