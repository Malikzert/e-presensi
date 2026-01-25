<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Models\Unit;
use Illuminate\Http\Request;

class UJController extends Controller
{
    public function index()
    {
        return view('admin.UnitJabatan', [
            'jabatans' => Jabatan::all(),
            'units' => Unit::all()
        ]);
    }

    // CRUD JABATAN
    public function storeJabatan(Request $request) {
        $request->validate(['nama_jabatan' => 'required|unique:jabatans']);
        Jabatan::create($request->all());
        return back()->with('success', 'Jabatan berhasil ditambah');
    }

    public function updateJabatan(Request $request, $id) {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());
        return back()->with('success', 'Jabatan berhasil diupdate');
    }

    public function destroyJabatan($id) {
        Jabatan::destroy($id);
        return back()->with('success', 'Jabatan dihapus');
    }

    // CRUD UNIT
    public function storeUnit(Request $request) 
{
    $request->validate([
        'kode_unit' => 'required|unique:units,kode_unit',
        'nama_unit' => 'required'
    ]);

    Unit::create([
        'kode_unit' => $request->kode_unit,
        'nama_unit' => $request->nama_unit
    ]);

    return back()->with('success', 'Unit berhasil ditambahkan!');
}

    public function updateUnit(Request $request, $id) 
    {
        $unit = Unit::findOrFail($id);
        
        $request->validate([
            'kode_unit' => 'required|unique:units,kode_unit,'.$id,
            'nama_unit' => 'required'
        ]);

        $unit->update([
            'kode_unit' => $request->kode_unit,
            'nama_unit' => $request->nama_unit
        ]);

        return back()->with('success', 'Unit berhasil diperbarui!');
    }

    public function destroyUnit($id) {
        Unit::destroy($id);
        return back()->with('success', 'Unit dihapus');
    }
}