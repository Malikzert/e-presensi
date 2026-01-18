<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // PROTEKSI: Jangan hapus jika foto lama adalah default.jpg
            if ($user->foto && $user->foto !== 'default.jpg') {
                $oldPath = public_path('images/users/' . $user->foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('foto');
            // Menambahkan prefix admin agar terlihat rapi di folder
            $nama_file = time() . '_admin.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/users'), $nama_file);
            
            // Simpan ke database
            $user->foto = $nama_file;
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}