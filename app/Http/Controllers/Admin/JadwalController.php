<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Shift;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Menampilkan halaman index dengan format Matriks/Roster
     */
    public function index(Request $request)
    {
        // 1. Data referensi untuk Modal Input
        $Karyawans = Karyawan::where('is_admin', false)->get();
        $shifts = Shift::all();

        // 2. Ambil parameter filter (Default bulan ini)
        $search = $request->input('search');
        $bulanInput = $request->input('bulan', Carbon::now()->format('Y-m'));
        
        // Parsing tanggal untuk mendapatkan info bulan/hari
        $date = Carbon::parse($bulanInput);
        $daysInMonth = $date->daysInMonth;

        // 3. Query Utama (Mengambil Karyawan dan Jadwalnya pada bulan terpilih)
        $query = Karyawan::where('is_admin', false)
            ->with(['jabatan', 'jadwals' => function($q) use ($date) {
                $q->whereMonth('tanggal', $date->month)
                  ->whereYear('tanggal', $date->year)
                  ->with('shift');
            }]);

        // Fitur Pencarian Nama Karyawan
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // 4. Eksekusi dengan Pagination
        $karyawans = $query->paginate(10);

        return view('admin.jadwals', compact(
            'Karyawans', 
            'shifts', 
            'karyawans', 
            'daysInMonth', 
            'bulanInput'
        ));
    }
    public function storeShift(Request $request)
    {
        $request->validate([
            'nama_shift' => 'required',
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
        ]);

        Shift::updateOrCreate(
            ['id' => $request->shift_id], // Jika ada ID maka update, jika tidak maka create
            [
                'nama_shift' => $request->nama_shift,
                'jam_masuk'  => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
            ]
        );

        return back()->with('success', 'Data Shift berhasil diperbarui.');
    }

    /**
     * Hapus Shift
     */
    public function destroyShift($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return back()->with('success', 'Shift berhasil dihapus.');
    }
    /**
     * Fitur Auto-fill Jadwal dari bulan sebelumnya
     */
    public function autofill(Request $request)
    {
        $request->validate([
            'dari_bulan' => 'required|date_format:Y-m',
            'ke_bulan' => 'required|date_format:Y-m',
        ]);

        $dari = Carbon::parse($request->dari_bulan);
        $ke = Carbon::parse($request->ke_bulan);

        // Ambil semua jadwal dari bulan asal
        $jadwalAsal = Jadwal::whereMonth('tanggal', $dari->month)
            ->whereYear('tanggal', $dari->year)
            ->get();

        $count = 0;
        foreach ($jadwalAsal as $item) {
            // Tentukan tanggal baru dengan hari yang sama di bulan tujuan
            $hariAsal = Carbon::parse($item->tanggal)->day;
            
            // Pastikan tanggal tersebut valid di bulan tujuan (misal tgl 31 di bulan Feb)
            if ($hariAsal <= $ke->daysInMonth) {
                $tanggalBaru = Carbon::create($ke->year, $ke->month, $hariAsal)->format('Y-m-d');

                // Cek apakah Karyawan sudah punya jadwal di tanggal tersebut
                $exists = Jadwal::where('karyawan_id', $item->karyawan_id)
                    ->where('tanggal', $tanggalBaru)
                    ->exists();

                if (!$exists) {
                    Jadwal::create([
                        'karyawan_id' => $item->karyawan_id,
                        'shift_id' => $item->shift_id,
                        'tanggal' => $tanggalBaru,
                        'keterangan' => $item->keterangan
                    ]);
                    $count++;
                }
            }
        }

        return back()->with('success', "$count data jadwal berhasil disalin secara otomatis.");
    }

    /**
     * Simpan jadwal baru atau update jika tanggal & Karyawan sama
     */
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required',
            'shift_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        // Menggunakan updateOrCreate agar admin bisa mengganti shift langsung
        Jadwal::updateOrCreate(
            [
                'karyawan_id' => $request->karyawan_id, 
                'tanggal' => $request->tanggal
            ],
            [
                'shift_id' => $request->shift_id, 
                'keterangan' => $request->keterangan
            ]
        );

        return back()->with('success', 'Jadwal berhasil disimpan.');
    }

    /**
     * Update jadwal spesifik (biasanya via ID)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'karyawan_id' => 'required',
            'shift_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'karyawan_id' => $request->karyawan_id,
            'shift_id' => $request->shift_id,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return back()->with('success', 'Jadwal berhasil diubah.');
    }

    /**
     * Hapus jadwal spesifik
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}