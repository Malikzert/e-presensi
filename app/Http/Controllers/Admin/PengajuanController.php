<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest; // Pastikan nama model sesuai (misal: Pengajuan atau LeaveRequest)
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        // Mengambil semua pengajuan, yang statusnya 'pending' ditaruh paling atas
        $pengajuans = LeaveRequest::with('user')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->latest()
            ->paginate(10);

        return view('admin.pengajuans', compact('pengajuans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $pengajuan = LeaveRequest::findOrFail($id);
        $pengajuan->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }
}
