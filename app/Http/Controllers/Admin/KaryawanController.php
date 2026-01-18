<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('nik', 'LIKE', "%{$search}%")
                ->orWhere('jabatan', 'LIKE', "%{$search}%");
            });
        }

        $karyawans = $query->latest()->paginate(10);
        return view('admin.karyawans', compact('karyawans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nik' => 'required|unique:users,nik',
            'jabatan' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['is_admin'] = false;
        $data['status'] = 'aktif';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $nama_file);
            $data['foto'] = $nama_file;
        }

        User::create($data);
        return back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|unique:users,nik,' . $user->id,
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except(['password', 'foto']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // PROTEKSI: Hanya hapus foto lama jika ada DAN bukan 'default.jpg'
            if ($user->foto && $user->foto !== 'default.jpg') {
                $old_path = public_path('images/users/' . $user->foto);
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }

            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $nama_file);
            
            // Masukkan nama file baru ke data update
            $data['foto'] = $nama_file;
        }

        $user->update($data);
        return back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // PROTEKSI: Jangan hapus file jika itu adalah 'default.jpg'
        if ($user->foto && $user->foto !== 'default.jpg') {
            $path = public_path('images/users/' . $user->foto);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $user->delete();
        return back()->with('success', 'Karyawan berhasil dihapus!');
    }
}