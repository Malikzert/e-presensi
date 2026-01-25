<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\User;
use App\Models\Shift;
use App\Models\Jadwal; // Tambahkan ini
use Illuminate\Http\Request;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $query = Kehadiran::with('user');

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        $kehadirans = $query->orderBy('tanggal', 'desc')->paginate(10);
        $kehadirans = $query->paginate(10)->withQueryString();
        
        $users = User::where('is_admin', false)->get();
        $shifts = Shift::all(); 

        return view('admin.kehadirans', compact('kehadirans', 'users', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'shift_id' => 'required',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required',
            'lokasi_masuk' => 'nullable',
        ]);

        // LOGIKA PENGECEKAN JADWAL OTOMATIS
        // Cek apakah user memiliki jadwal di tanggal tersebut
        $jadwal = Jadwal::where('user_id', $request->user_id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();

        // Jika ada jadwal, paksa shift_id mengikuti jadwal agar data sinkron
        if ($jadwal) {
            $request->merge(['shift_id' => $jadwal->shift_id]);
        }

        $data = $request->all();
        
        // Logika Otomatis Terlambat (Toleransi 1 Jam)
        $shift = Shift::find($request->shift_id);
        if ($shift && ($request->status == 'Hadir' || $request->status == 'Hadir (Terlambat)')) {
            $jamMasukShift = Carbon::parse($shift->jam_masuk);
            $batasTerlambat = $jamMasukShift->copy()->addHours(1);
            $jamInputUser = Carbon::parse($request->jam_masuk);

            if ($jamInputUser->gt($batasTerlambat)) {
                $data['status'] = 'Hadir (Terlambat)';
            } else {
                $data['status'] = 'Hadir';
            }
        }

        // Tangkap IP Publik yang sedang terhubung
        $currentIp = $request->ip();
        $data['ip_address_masuk'] = $currentIp;
        
        if ($request->filled('jam_pulang')) {
            $data['ip_address_pulang'] = $currentIp;
        }

        Kehadiran::create($data);
        return back()->with('success', 'Kehadiran berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $status = $request->status;
        $currentIp = $request->ip();

        // Logika Otomatis Terlambat saat Edit (Toleransi 1 Jam)
        $shift = Shift::find($kehadiran->shift_id);
        if ($shift && ($status == 'Hadir' || $status == 'Hadir (Terlambat)')) {
            $jamMasukShift = Carbon::parse($shift->jam_masuk);
            $batasTerlambat = $jamMasukShift->copy()->addHours(1);
            $jamInputUser = Carbon::parse($request->jam_masuk);

            if ($jamInputUser->gt($batasTerlambat)) {
                $status = 'Hadir (Terlambat)';
            } else {
                $status = 'Hadir';
            }
        }
        
        $updateData = [
            'jam_masuk'     => $request->jam_masuk,
            'jam_pulang'    => $request->jam_pulang,
            'status'        => $status,
            'lokasi_masuk'  => $request->lokasi_masuk,
            'lokasi_pulang' => $request->lokasi_pulang,
        ];

        if (empty($kehadiran->ip_address_masuk)) {
            $updateData['ip_address_masuk'] = $currentIp;
        }

        if (empty($kehadiran->ip_address_pulang) && $request->filled('jam_pulang')) {
            $updateData['ip_address_pulang'] = $currentIp;
        }

        $kehadiran->update($updateData);

        return back()->with('success', 'Data kehadiran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Kehadiran::findOrFail($id)->delete();
        return back()->with('success', 'Data kehadiran dihapus');
    }
}