<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
    </style>

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" 
         x-data="{ 
            openModal: false, 
            openAutofillModal: false,
            openShiftModal: false,
            editMode: false, 
            deleteModal: false, 
            deleteAction: '', 
            currentJadwal: { id: '', karyawan_id: '', shift_id: '', tanggal: '' },
            currentShift: { id: '', nama_shift: '', jam_masuk: '', jam_pulang: '' }
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Manajemen Jadwal & Shift'])

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-600 text-white rounded-2xl shadow-lg font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-6">
                <div>
                    <h2 class="text-xl font-black text-emerald-900 tracking-tight">Data Plotting</h2>
                    <p class="text-emerald-600 font-bold text-xs uppercase tracking-widest opacity-70">Pengaturan Shift RSU ANNA MEDIKA</p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button @click="openAutofillModal = true" 
                            class="bg-white text-emerald-700 border-2 border-emerald-100 px-6 py-3 rounded-2xl font-black text-sm shadow-sm flex items-center gap-2 hover:border-emerald-500 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Auto-Fill
                    </button>

                    {{-- Tombol Kelola Shift Baru --}}
                    <button @click="openShiftModal = true" 
                            class="bg-white text-emerald-700 border-2 border-emerald-100 px-6 py-3 rounded-2xl font-black text-sm shadow-sm flex items-center gap-2 hover:border-emerald-500 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kelola Shift
                    </button>

                    <button @click="openModal = true; editMode = false; currentJadwal = { karyawan_id: '', shift_id: '', tanggal: '' }" 
                            class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-emerald-200 flex items-center gap-2 hover:bg-emerald-700 transition-all w-fit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Plotting Baru
                    </button>
                </div>
            </div>

            {{-- Filter & Search --}}
            <div class="bg-white/60 backdrop-blur-md p-6 rounded-[35px] border border-white/80 shadow-xl mb-8">
                <form action="{{ route('admin.jadwals') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[250px] relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Karyawan..." 
                               class="w-full bg-white border-emerald-100 rounded-2xl py-3 px-5 pl-12 text-sm focus:ring-emerald-500 shadow-sm transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="w-full md:w-48">
                        <input type="month" name="bulan" value="{{ request('bulan', $bulanInput) }}" 
                               class="w-full bg-white border-emerald-100 rounded-2xl py-3 px-5 text-sm focus:ring-emerald-500 shadow-sm">
                    </div>
                    <button type="submit" class="bg-white text-emerald-700 border border-emerald-200 px-6 py-3 rounded-2xl font-black text-xs hover:bg-emerald-50 transition-all">
                        Filter
                    </button>
                </form>
            </div>

            {{-- Table Matriks Roster --}}
            <div class="bg-white/80 backdrop-blur-md rounded-[40px] border border-white shadow-2xl overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-emerald-600 text-white text-[10px] uppercase tracking-wider font-black">
                                <th class="px-6 py-4 sticky left-0 bg-emerald-600 z-20 shadow-md min-w-[200px]">Nama Karyawan</th>
                                @for($i = 1; $i <= $daysInMonth; $i++)
                                    <th class="px-3 py-4 text-center border-l border-emerald-500/30">{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50">
                            @forelse($karyawans as $karyawan)
                            <tr class="group hover:bg-emerald-50/50 transition-all">
                                <td class="px-6 py-4 sticky left-0 bg-white/95 backdrop-blur-sm z-10 shadow-sm group-hover:bg-emerald-50 transition-all">
                                    <p class="font-black text-gray-800 text-sm truncate uppercase">{{ $karyawan->name }}</p>
                                    <p class="text-[9px] text-emerald-600 font-bold uppercase tracking-tighter">{{ $karyawan->jabatan->nama_jabatan ?? 'Staf' }}</p>
                                </td>

                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $currentDate = \Carbon\Carbon::parse($bulanInput)->day($d)->format('Y-m-d');
                                        $jadwalHariIni = $karyawan->jadwals->firstWhere('tanggal', $currentDate);
                                        $inisial = '';
                                        $bgColor = 'bg-gray-50 text-gray-300';
                                        
                                        if($jadwalHariIni) {
                                            $namaShift = strtoupper($jadwalHariIni->shift->nama_shift);
                                            if(str_contains($namaShift, 'PAGI')) { $inisial = 'P'; $bgColor = 'bg-emerald-100 text-emerald-700'; }
                                            elseif(str_contains($namaShift, 'SIANG')) { $inisial = 'S'; $bgColor = 'bg-amber-100 text-amber-700'; }
                                            elseif(str_contains($namaShift, 'MALAM')) { $inisial = 'M'; $bgColor = 'bg-indigo-100 text-indigo-700'; }
                                            elseif(str_contains($namaShift, 'MIDDLE')) { $inisial = 'MD'; $bgColor = 'bg-sky-100 text-sky-700'; }
                                            elseif(str_contains($namaShift, 'LIBUR')) { $inisial = 'L'; $bgColor = 'bg-rose-100 text-rose-600'; }
                                            else { $inisial = substr($namaShift, 0, 1); $bgColor = 'bg-emerald-100 text-emerald-700'; }
                                        }
                                    @endphp
                                    
                                    <td class="p-1 border-l border-emerald-50 text-center">
                                        <div @click="openModal = true; editMode = {{ $jadwalHariIni ? 'true' : 'false' }}; 
                                                    currentJadwal = { 
                                                        id: '{{ $jadwalHariIni->id ?? '' }}', 
                                                        karyawan_id: '{{ $karyawan->id }}', 
                                                        tanggal: '{{ $currentDate }}',
                                                        shift_id: '{{ $jadwalHariIni->shift_id ?? '' }}'
                                                    }"
                                             class="w-9 h-9 mx-auto flex items-center justify-center rounded-xl text-[10px] font-black cursor-pointer hover:scale-110 active:scale-95 transition-all {{ $bgColor }} border border-transparent hover:border-emerald-300 shadow-sm">
                                            {{ $inisial ?: '-' }}
                                        </div>
                                    </td>
                                @endfor
                            </tr>
                            @empty
                            <tr><td colspan="{{ $daysInMonth + 1 }}" class="px-8 py-20 text-center italic opacity-30">Karyawan tidak ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Legend & Pagination --}}
            <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-wrap gap-3 p-4 bg-white/50 backdrop-blur-sm rounded-3xl border border-white shadow-sm">
                    <span class="text-[9px] font-black text-emerald-800 uppercase mr-2">Keterangan:</span>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-600"><span class="w-3 h-3 bg-emerald-100 border border-emerald-200 rounded-sm"></span> PAGI</div>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-600"><span class="w-3 h-3 bg-amber-100 border border-amber-200 rounded-sm"></span> SIANG</div>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-600"><span class="w-3 h-3 bg-indigo-100 border border-indigo-200 rounded-sm"></span> MALAM</div>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-600"><span class="w-3 h-3 bg-sky-100 border border-sky-200 rounded-sm"></span> MIDDLE</div>
                    <div class="flex items-center gap-1.5 text-[9px] font-bold text-gray-600"><span class="w-3 h-3 bg-rose-100 border border-rose-200 rounded-sm"></span> LIBUR</div>
                </div>
                <div>{{ $karyawans->appends(request()->query())->links() }}</div>
            </div>
        </div>

        {{-- Modal Kelola Shift (BARU) --}}
        <div x-show="openShiftModal" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-emerald-900/40 backdrop-blur-sm">
            <div @click.away="openShiftModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-2xl shadow-2xl border border-emerald-100 overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-emerald-900">Konfigurasi Shift</h3>
                    <button @click="openShiftModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <form action="{{ route('admin.shift.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-8 bg-emerald-50 p-6 rounded-[30px] border border-emerald-100">
                    @csrf
                    <input type="hidden" name="shift_id" x-model="currentShift.id">
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Nama Shift</label>
                        <input type="text" name="nama_shift" x-model="currentShift.nama_shift" placeholder="Contoh: Pagi 1" class="w-full rounded-xl border-emerald-100 p-2.5 text-sm font-bold" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Masuk</label>
                        <input type="time" name="jam_masuk" x-model="currentShift.jam_masuk" class="w-full rounded-xl border-emerald-100 p-2.5 text-sm font-bold" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Pulang</label>
                        <input type="time" name="jam_pulang" x-model="currentShift.jam_pulang" class="w-full rounded-xl border-emerald-100 p-2.5 text-sm font-bold" required>
                    </div>
                    <button type="submit" class="md:col-span-4 bg-emerald-600 text-white py-3 rounded-xl font-black text-xs shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">
                        Simpan Master Shift
                    </button>
                    <template x-if="currentShift.id">
                        <button type="button" @click="currentShift = { id: '', nama_shift: '', jam_masuk: '', jam_pulang: '' }" class="md:col-span-4 text-[10px] font-bold text-emerald-500 underline uppercase">Batal Edit / Reset Form</button>
                    </template>
                </form>

                <div class="max-h-[300px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-left">
                        <thead class="sticky top-0 bg-white">
                            <tr class="text-[10px] font-black text-emerald-400 uppercase tracking-widest border-b border-emerald-50">
                                <th class="pb-3 pl-2">Nama Shift</th>
                                <th class="pb-3 text-center">Waktu</th>
                                <th class="pb-3 text-right pr-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50">
                            @foreach($shifts as $s)
                            <tr class="group hover:bg-indigo-50/30 transition-all">
                                <td class="py-4 font-black text-sm text-indigo-900 pl-2 uppercase">{{ $s->nama_shift }}</td>
                                <td class="py-4 text-center"><span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-[10px] font-black">{{ $s->jam_masuk }} - {{ $s->jam_pulang }}</span></td>
                                <td class="py-4 text-right pr-2">
                                    <div class="flex justify-end gap-2">
                                        <button @click="currentShift = { id: '{{ $s->id }}', nama_shift: '{{ $s->nama_shift }}', jam_masuk: '{{ $s->jam_masuk }}', jam_pulang: '{{ $s->jam_pulang }}' }" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        <form action="{{ route('admin.shift.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus shift ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Autofill --}}
        <div x-show="openAutofillModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-emerald-900/40 backdrop-blur-sm">
            <div @click.away="openAutofillModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-md shadow-2xl border border-emerald-100">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-emerald-900">Auto-Fill Jadwal</h3>
                </div>

                <form action="{{ route('admin.jadwals.autofill') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Salin Dari Bulan</label>
                        <input type="month" name="dari_bulan" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required value="{{ \Carbon\Carbon::parse($bulanInput)->subMonth()->format('Y-m') }}">
                        <input type="hidden" name="ke_bulan" value="{{ $bulanInput }}">
                    </div>
                    <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 text-[10px] text-amber-700 font-bold uppercase leading-relaxed">⚠️ Hanya mengisi tanggal kosong.</div>
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="openAutofillModal = false" class="flex-1 py-3.5 rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-emerald-600 text-white rounded-2xl font-black shadow-lg shadow-emerald-200">Eksekusi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Plotting/Edit --}}
        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-emerald-900/40 backdrop-blur-sm">
            <div @click.away="openModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-md shadow-2xl border border-emerald-100">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-2xl font-black text-emerald-900" x-text="editMode ? 'Ubah Jadwal' : 'Plotting Jadwal Baru'"></h3>
                    <template x-if="editMode">
                        <button @click="openModal = false; deleteModal = true; deleteAction = `/admin/jadwals/${currentJadwal.id}`" 
                                class="text-rose-500 hover:text-rose-700 p-2 bg-rose-50 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </template>
                </div>

                <form :action="editMode ? `/admin/jadwals/${currentJadwal.id}` : '{{ route('admin.jadwals.store') }}'" method="POST" class="space-y-5">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Karyawan</label>
                        <select name="karyawan_id" x-model="currentJadwal.karyawan_id" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($Karyawans as $Karyawan)<option value="{{ $Karyawan->id }}">{{ $Karyawan->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Shift</label>
                        <select name="shift_id" x-model="currentJadwal.shift_id" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required>
                            <option value="">-- Pilih Shift --</option>
                            @foreach($shifts as $shift)<option value="{{ $shift->id }}">{{ $shift->nama_shift }} ({{ $shift->jam_masuk }}-{{ $shift->jam_pulang }})</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Tanggal</label>
                        <input type="date" name="tanggal" x-model="currentJadwal.tanggal" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required>
                    </div>
                    <div class="flex gap-3 mt-8">
                        <button type="button" @click="openModal = false" class="flex-1 py-3.5 rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-emerald-600 text-white rounded-2xl font-black shadow-lg shadow-emerald-200">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Delete --}}
        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-rose-900/20 backdrop-blur-sm">
            <div @click.away="deleteModal = false" class="bg-white rounded-[40px] p-8 w-full max-sm shadow-2xl border border-rose-100 text-center">
                <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Jadwal?</h3>
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <form :action="deleteAction" method="POST" class="flex-1">@csrf @method('DELETE')<button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-2xl font-black">Hapus</button></form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>