<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Style tambahan agar scrollbar horizontal tetap cantik */
        .custom-scrollbar::-webkit-scrollbar { height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
    </style>

    {{-- Background Image & Gradient --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" 
         x-data="{ 
            openModal: false, 
            openAutofillModal: false,
            editMode: false, 
            deleteModal: false, 
            deleteAction: '', 
            currentJadwal: { id: '', user_id: '', shift_id: '', tanggal: '' } 
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Bagian Navigasi --}}
            @include('admin.navs', ['title' => 'Manajemen Jadwal & Shift'])

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-600 text-white rounded-2xl shadow-lg font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header Area & Button --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-6">
                <div>
                    <h2 class="text-xl font-black text-emerald-900 tracking-tight">Data Plotting</h2>
                    <p class="text-emerald-600 font-bold text-xs uppercase tracking-widest opacity-70">Pengaturan Shift RSU ANNA MEDIKA</p>
                </div>
                
                <div class="flex gap-3">
                    {{-- Tombol Autofill --}}
                    <button @click="openAutofillModal = true" 
                            class="bg-white text-emerald-700 border-2 border-emerald-100 px-6 py-3 rounded-2xl font-black text-sm shadow-sm flex items-center gap-2 hover:border-emerald-500 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Auto-Fill Jadwal
                    </button>

                    <button @click="openModal = true; editMode = false; currentJadwal = { user_id: '', shift_id: '', tanggal: '' }" 
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
                                                        user_id: '{{ $karyawan->id }}', 
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
                            <tr>
                                <td colspan="{{ $daysInMonth + 1 }}" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="font-black text-lg">Karyawan tidak ditemukan</p>
                                    </div>
                                </td>
                            </tr>
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

                <div class="w-full md:w-auto">
                    {{ $karyawans->appends(request()->query())->links() }}
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
                    <p class="text-sm text-gray-500">Salin pola shift karyawan dari bulan sebelumnya ke bulan ini secara otomatis.</p>
                </div>

                <form action="{{ route('admin.jadwals.autofill') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Salin Dari Bulan</label>
                        <input type="month" name="dari_bulan" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required value="{{ \Carbon\Carbon::parse($bulanInput)->subMonth()->format('Y-m') }}">
                        <input type="hidden" name="ke_bulan" value="{{ $bulanInput }}">
                    </div>
                    
                    <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                        <p class="text-[10px] text-amber-700 font-bold leading-relaxed">
                            <span class="block text-xs mb-1">⚠️ INFO:</span>
                            Hanya mengisi tanggal yang masih kosong. Jadwal yang sudah ada tidak akan tertimpa.
                        </p>
                    </div>

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
                        <select name="user_id" x-model="currentJadwal.user_id" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3.5 text-sm font-bold" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach
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
            <div @click.away="deleteModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-sm shadow-2xl border border-rose-100 text-center">
                <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Jadwal?</h3>
                <p class="text-sm text-gray-500 mb-6">Data jadwal pada tanggal tersebut akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <form :action="deleteAction" method="POST" class="flex-1">@csrf @method('DELETE')<button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-2xl font-black">Hapus</button></form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>