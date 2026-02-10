<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Karyawan;
use App\Models\Shift;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class KehadiranController extends Controller
{
    // Daftar IP yang diperbolehkan (Sesuaikan dengan IP Publik RS Anda)
    protected $allowedIps = ['127.0.0.1', '192.168.1.1']; 

    /**
     * Privat fungsi untuk validasi IP
     */
    private function isIpAllowed($ip)
    {
        return in_array($ip, $this->allowedIps);
    }

    public function index(Request $request)
    {
        $query = Kehadiran::with('Karyawan');

        if ($request->has('search')) {
            $query->whereHas('Karyawan', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        $kehadirans = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        
        $karyawans = Karyawan::where('is_admin', false)->get();
        $shifts = Shift::all(); 

        return view('admin.kehadirans', compact('kehadirans', 'karyawans', 'shifts'));
    }

    public function store(Request $request)
    {
        // --- VALIDASI IP ---
        if (!$this->isIpAllowed($request->ip())) {
            return back()->with('error', 'Akses ditolak. Anda harus terhubung ke jaringan WiFi Kantor untuk menambah data.');
        }

        $request->validate([
            'karyawan_id' => 'required',
            'shift_id' => 'required',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'status' => 'required',
            'lokasi_masuk' => 'nullable',
        ]);

        $jadwal = Jadwal::where('karyawan_id', $request->karyawan_id)
                        ->whereDate('tanggal', $request->tanggal)
                        ->first();

        if ($jadwal) {
            $request->merge(['shift_id' => $jadwal->shift_id]);
        }

        $data = $request->all();
        
        $shift = Shift::find($request->shift_id);
        if ($shift && in_array($request->status, ['Hadir', 'Hadir (Terlambat)', 'hadir'])) {
            $jamMasukShift = Carbon::parse($request->tanggal . ' ' . $shift->jam_masuk);
            $batasTerlambat = $jamMasukShift->copy()->addMinutes(15);
            $jamInputKaryawan = Carbon::parse($request->tanggal . ' ' . $request->jam_masuk);

            if ($jamInputKaryawan->gt($batasTerlambat)) {
                $data['status'] = 'Hadir (Terlambat)';
            } else {
                $data['status'] = 'hadir'; 
            }
        }

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
        // --- VALIDASI IP ---
        if (!$this->isIpAllowed($request->ip())) {
            return back()->with('error', 'Akses ditolak. Anda harus terhubung ke jaringan WiFi Kantor untuk mengubah data.');
        }

        $kehadiran = Kehadiran::findOrFail($id);
        $status = $request->status;
        $currentIp = $request->ip();

        $shift = Shift::find($kehadiran->shift_id);
        if ($shift && in_array($status, ['Hadir', 'Hadir (Terlambat)', 'hadir'])) {
            $jamMasukShift = Carbon::parse($kehadiran->tanggal . ' ' . $shift->jam_masuk);
            $batasTerlambat = $jamMasukShift->copy()->addMinutes(15);
            $jamInputKaryawan = Carbon::parse($kehadiran->tanggal . ' ' . $request->jam_masuk);

            if ($jamInputKaryawan->gt($batasTerlambat)) {
                $status = 'Hadir (Terlambat)';
            } else {
                $status = 'hadir';
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