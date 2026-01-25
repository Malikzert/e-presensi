<x-app-layout>
    <div class="relative py-8 bg-gray-50 min-h-screen">
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/60 via-gray-50/40 to-white/80"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
                <div class="bg-white/40 backdrop-blur-md p-4 rounded-xl border border-white/50 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-gray-800 border-l-4 border-emerald-500 pl-4">
                        Jadwal Dinas Kerja
                    </h2>
                    <p class="text-sm text-gray-600 mt-1 pl-5">Lihat dan pantau jadwal shift kerja Anda</p>
                </div>

                <form action="{{ route('jadwal') }}" method="GET" class="flex items-center gap-2 bg-white/80 backdrop-blur-sm p-2 rounded-xl shadow-sm border border-emerald-100">
                    <select name="bulan" class="rounded-lg border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white/50">
                        @foreach($daftarBulan as $m => $namaBulan)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="rounded-lg border-gray-200 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-white/50">
                        @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow-lg shadow-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($jadwalUser as $j)
                    @php
                        $isToday = $j->tanggal == date('Y-m-d');
                        $carbonTanggal = \Carbon\Carbon::parse($j->tanggal);
                    @endphp
                    <div class="relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border {{ $isToday ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-gray-100' }} overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        
                        @if($isToday)
                            <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest z-10">
                                Hari Ini
                            </div>
                        @endif

                        <div class="p-5">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="bg-emerald-50 text-emerald-700 rounded-xl p-3 text-center min-w-[60px] shadow-sm border border-emerald-100">
                                    <span class="block text-xs uppercase font-bold">{{ $carbonTanggal->translatedFormat('D') }}</span>
                                    <span class="block text-xl font-extrabold">{{ $carbonTanggal->format('d') }}</span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800">{{ $carbonTanggal->translatedFormat('F Y') }}</h3>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-bold uppercase tracking-tighter">Status: Aktif</span>
                                </div>
                            </div>

                            <hr class="border-gray-100 mb-4">

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium tracking-tight">Nama Shift</span>
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-md font-bold uppercase">
                                        {{ $j->shift->nama_shift }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium tracking-tight">Jam Kerja</span>
                                    <div class="flex items-center gap-1 text-emerald-600 font-bold">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-sm font-mono tracking-tighter">{{ substr($j->shift->jam_masuk, 0, 5) }} - {{ substr($j->shift->jam_pulang, 0, 5) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-gray-50/50 flex justify-between items-center border-t border-gray-100">
                             <span class="text-[9px] text-gray-400 font-bold tracking-widest uppercase">Anna Medika</span>
                             <div class="h-2 w-2 rounded-full {{ $isToday ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' }}"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white/60 backdrop-blur-md rounded-3xl border-2 border-dashed border-gray-300 shadow-inner">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-gray-500 font-bold text-lg">Belum ada jadwal</p>
                        <p class="text-gray-400 text-sm">Silahkan hubungi admin atau pilih bulan lain.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>