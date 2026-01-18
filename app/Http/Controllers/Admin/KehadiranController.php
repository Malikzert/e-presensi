<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Filter Tanggal (Default hari ini jika tidak ada input)
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        // Mengambil data kehadiran sesuai tanggal
        // Kita gunakan Eager Loading (with('user')) agar tidak lambat saat load data user
        $kehadirans = Attendance::with('user')
            ->whereDate('tanggal', $tanggal)
            ->latest()
            ->paginate(15); // Menggunakan pagination agar rapi jika data banyak

        return view('admin.kehadirans', compact('kehadirans', 'tanggal'));
    }

    // Fungsi tambahan jika Admin ingin melihat detail lokasi absen
    public function show(Attendance $attendance)
    {
        return response()->json($attendance);
    }
}
