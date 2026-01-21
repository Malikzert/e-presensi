<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->with(['jabatan', 'units']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('nik', 'LIKE', "%{$search}%")
                ->orWhere('nopeg', 'LIKE', "%{$search}%") // Tambahan search by Nopeg
                ->orWhere('gender', 'LIKE', "%{$search}%") // Tambahan search by Gender
                ->orWhereHas('jabatan', function($j) use ($search) {
                    $j->where('nama_jabatan', 'LIKE', "%{$search}%");
                });
            });
        }

        $karyawans = $query->latest()->paginate(10);
        
        $jabatans = Jabatan::all();
        $units = Unit::all();

        return view('admin.karyawans', compact('karyawans', 'jabatans', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nik' => 'required|unique:users,nik',
            'nopeg' => 'required|unique:users,nopeg', // Validasi Nopeg Baru
            'gender' => 'required|in:Laki-laki,Perempuan', // Validasi Gender Baru
            'jabatan_id' => 'required|exists:jabatans,id',
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
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

        $user = User::create($data);
        $user->units()->sync($request->unit_ids);

        return back()->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|unique:users,nik,' . $user->id,
            'nopeg' => 'required|unique:users,nopeg,' . $user->id, // Validasi Nopeg Update
            'gender' => 'required|in:Laki-laki,Perempuan', // Validasi Gender Update
            'jabatan_id' => 'required|exists:jabatans,id',
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except(['password', 'foto']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($user->foto && $user->foto !== 'default.jpg') {
                $old_path = public_path('images/users/' . $user->foto);
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }

            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $nama_file);
            $data['foto'] = $nama_file;
        }

        $user->update($data);
        $user->units()->sync($request->unit_ids);

        return back()->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->foto && $user->foto !== 'default.jpg') {
            $path = public_path('images/users/' . $user->foto);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $user->delete();
        return back()->with('success', 'Karyawan berhasil dihapus!');
    }

    public function storeJabatan(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan'
        ]);

        Jabatan::create($request->all());

        return back()->with('success', 'Jabatan baru berhasil ditambahkan!');
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'kode_unit' => 'required|unique:units,kode_unit',
            'nama_unit' => 'required'
        ]);

        Unit::create($request->all());

        return back()->with('success', 'Unit baru berhasil ditambahkan!');
    }
}