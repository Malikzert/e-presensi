<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        // Logic Baru: Ambil semua user (Admin & Karyawan) agar Tab di View berfungsi
        $query = Karyawan::with(['jabatan', 'units']);

        // Filter Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('nik', 'LIKE', "%{$search}%")
                ->orWhere('nopeg', 'LIKE', "%{$search}%")
                ->orWhere('gender', 'LIKE', "%{$search}%")
                ->orWhereHas('jabatan', function($j) use ($search) {
                    $j->where('nama_jabatan', 'LIKE', "%{$search}%");
                });
            });
        }

        // Fitur Filter: Khusus Permintaan Hapus
        if ($request->filter == 'delete_request') {
            $query->whereNotNull('delete_requested_at');
        }

        $karyawans = $query->latest()->paginate(12); // Ditingkatkan ke 12 agar grid 3 kolom simetris
        
        $jabatans = Jabatan::all();
        $units = Unit::all();

        return view('admin.karyawans', compact('karyawans', 'jabatans', 'units'));
    }

    /**
     * Logic: Menangani Persetujuan atau Penolakan Hapus Akun
     */
    public function handleDeletion(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        
        if ($request->action == 'approve') {
            if ($karyawan->foto && $karyawan->foto !== 'default.jpg') {
                $path = public_path('images/users/' . $karyawan->foto);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            
            $karyawan->delete(); 
            return redirect()->route('admin.karyawans')->with('success', 'Akun telah dihapus secara permanen.');
            
        } else {
            $karyawan->update([
                'delete_requested_at' => null,
                'status' => 'aktif'
            ]);
            
            return redirect()->route('admin.karyawans')->with('success', 'Permintaan penghapusan akun ditolak.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:karyawans,email',
            'password' => 'required|min:8',
            'nik' => 'required|unique:karyawans,nik',
            'nopeg' => 'required|unique:karyawans,nopeg',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'jabatan_id' => 'required|exists:jabatans,id',
            'kuota_cuti' => 'required|integer|min:0',
            'is_admin' => 'required|boolean', // Validasi input is_admin
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['status'] = 'aktif';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $nama_file);
            $data['foto'] = $nama_file;
        }

        $karyawan = Karyawan::create($data);
        $karyawan->units()->sync($request->unit_ids);

        return back()->with('success', 'Data berhasil ditambahkan!');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:karyawans,email,' . $karyawan->id,
            'nik' => 'required|unique:karyawans,nik,' . $karyawan->id,
            'nopeg' => 'required|unique:karyawans,nopeg,' . $karyawan->id,
            'gender' => 'required|in:Laki-laki,Perempuan',
            'jabatan_id' => 'required|exists:jabatans,id',
            'kuota_cuti' => 'required|integer|min:0',
            'is_admin' => 'required|boolean', // Validasi input is_admin
            'unit_ids' => 'required|array',
            'unit_ids.*' => 'exists:units,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except(['password', 'foto']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            if ($karyawan->foto && $karyawan->foto !== 'default.jpg') {
                $old_path = public_path('images/users/' . $karyawan->foto);
                if (File::exists($old_path)) {
                    File::delete($old_path);
                }
            }

            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $nama_file);
            $data['foto'] = $nama_file;
        }

        $karyawan->update($data);
        $karyawan->units()->sync($request->unit_ids);

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto && $karyawan->foto !== 'default.jpg') {
            $path = public_path('images/users/' . $karyawan->foto);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $karyawan->delete();
        return back()->with('success', 'Data berhasil dihapus!');
    }

    public function storeJabatan(Request $request)
    {
        $request->validate(['nama_jabatan' => 'required|unique:jabatans,nama_jabatan']);
        Jabatan::create($request->all());
        return back()->with('success', 'Jabatan baru berhasil ditambahkan!');
    }

    public function storeUnit(Request $request)
    {
        $request->validate(['kode_unit' => 'required|unique:units,kode_unit', 'nama_unit' => 'required']);
        Unit::create($request->all());
        return back()->with('success', 'Unit baru berhasil ditambahkan!');
    }
}