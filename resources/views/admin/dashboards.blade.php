<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
        {{-- Vignette Effect --}}
        <div class="absolute inset-0 shadow-[inset_0_0_150px_rgba(6,78,59,0.2)] pointer-events-none"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Admin Dashboards'])

            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h3 class="text-emerald-900 font-black text-xl">Ringkasan Operasional</h3>
                    <p class="text-emerald-600 text-xs font-bold uppercase tracking-widest">Data Real-time Hari Ini</p>
                </div>
                
                <a href="{{ route('admin.export.kehadiran') }}" class="flex items-center gap-2 bg-white/80 backdrop-blur-md border border-emerald-100 px-6 py-3 rounded-2xl text-emerald-700 font-bold text-sm shadow-lg shadow-emerald-900/5 hover:bg-emerald-600 hover:text-white transition-all group">
                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Laporan Bulanan (.xlsx)
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    // Pastikan variabel dari Controller ($totalKaryawan, $hadirHariIni, dll) sudah benar
                    $statsData = [
                        [
                            'label' => 'Total Karyawan',
                            'value' => $totalKaryawan,
                            'color' => 'emerald',
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                            'progress' => 100
                        ],
                        [
                            'label' => 'Hadir Hari Ini',
                            'value' => $hadirHariIni,
                            'color' => 'blue',
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'progress' => $totalKaryawan > 0 ? ($hadirHariIni / $totalKaryawan) * 100 : 0
                        ],
                        [
                        'label' => 'Terlambat',
                        'value' => $terlambat,
                        'color' => 'amber', // Warna Kuning/Oranye cerah
                        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                        'progress' => $hadirHariIni > 0 ? ($terlambat / $hadirHariIni) * 100 : 0
                        ],
                        [
                        'label' => 'Pengajuan Pending',
                        'value' => $pendingIzin,
                        'color' => 'rose', // Warna Merah (Rose) agar terlihat urgent
                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                        'progress' => $totalKaryawan > 0 ? min(($pendingIzin / $totalKaryawan) * 100, 100) : 0
                        ]
                    ];
                @endphp

                @foreach($statsData as $stat)
                    <div class="bg-white/80 backdrop-blur-md p-6 rounded-[30px] border border-emerald-100 shadow-xl shadow-emerald-900/5 hover:scale-105 transition-all group">
                        <div class="flex justify-between items-start mb-4">
                            <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest">{{ $stat['label'] }}</p>
                            <svg class="w-5 h-5 text-{{ $stat['color'] }}-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-emerald-900">{{ number_format($stat['value'], 0, ',', '.') }}</h3>
                        
                        <div class="w-full bg-gray-100 h-1.5 mt-4 rounded-full overflow-hidden">
                            <div class="bg-{{ $stat['color'] }}-500 h-full transition-all duration-1000" style="width: {{ $stat['progress'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white/80 backdrop-blur-md rounded-[35px] border border-emerald-100 p-8 shadow-xl">
                    <h3 class="font-black text-emerald-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-emerald-500 rounded-full"></span> Aktivitas Terbaru
                    </h3>
                    <div class="space-y-4">
                        @forelse($latestActivities as $activity)
                        <div class="flex items-center justify-between p-4 bg-emerald-50/50 rounded-2xl border border-emerald-50 hover:bg-white transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                    {{ substr($activity->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $activity->user->name }}</p>
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-tighter">
                                        {{ $activity->status }} • {{ \Carbon\Carbon::parse($activity->jam_masuk)->format('H:i') }} WIB
                                    </p>
                                </div>
                            </div>
                            <span class="text-[10px] font-black {{ $activity->status == 'terlambat' ? 'text-rose-500' : 'text-emerald-500' }} uppercase tracking-widest bg-white px-3 py-1 rounded-lg border border-emerald-100 shadow-sm">
                                {{ $activity->status }}
                            </span>
                        </div>
                        @empty
                        <p class="text-center py-10 text-gray-400 font-bold italic text-sm">Belum ada aktivitas hari ini</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-md rounded-[35px] border border-emerald-100 p-8 shadow-xl flex flex-col justify-center items-center text-center">
                    <div class="relative w-40 h-40 flex items-center justify-center">
                        @php
                            $persentaseHadir = $totalKaryawan > 0 ? round(($hadirHariIni / $totalKaryawan) * 100) : 0;
                        @endphp
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-emerald-100" />
                            <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" 
                                stroke-dasharray="440" stroke-dashoffset="{{ 440 - (440 * $persentaseHadir / 100) }}"
                                class="text-emerald-600 transition-all duration-1000" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-emerald-900">{{ $persentaseHadir }}%</span>
                            <span class="text-[10px] font-bold text-emerald-600 uppercase">Hadir</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-gray-500 text-sm font-bold">Tingkat Kehadiran Hari Ini</p>
                        <p class="text-[10px] text-emerald-600 font-bold uppercase mt-1">RSU ANNA MEDIKA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>