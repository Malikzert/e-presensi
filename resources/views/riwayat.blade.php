<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" x-data="{ tab: 'kehadiran' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-emerald-800 tracking-tight">Riwayat & Aktivitas</h2>
                <p class="text-gray-600 font-medium">Pantau catatan kehadiran dan status pengajuan Anda secara real-time.</p>
                
                <div class="flex mt-6 bg-white/50 backdrop-blur-md p-1.5 rounded-2xl shadow-sm border border-emerald-100 w-fit">
                    <button @click="tab = 'kehadiran'" 
                        :class="tab === 'kehadiran' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-gray-500 hover:text-emerald-600'"
                        class="px-8 py-2.5 rounded-xl font-bold transition-all duration-300 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Kehadiran
                    </button>
                    <button @click="tab = 'pengajuan'" 
                        :class="tab === 'pengajuan' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-gray-500 hover:text-emerald-600'"
                        class="px-8 py-2.5 rounded-xl font-bold transition-all duration-300 text-sm flex items-center gap-2 relative">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Status Pengajuan
                        @if(auth()->user()->unreadNotifications->where('type', 'App\Notifications\StatusPengajuanNotification')->count() > 0)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                </div>
            </div>

            {{-- Tab Kehadiran --}}
            <div x-show="tab === 'kehadiran'" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="space-y-4">
                
                <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-[30px] border border-emerald-100 overflow-hidden transition-all hover:shadow-2xl hover:shadow-emerald-200/40">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-emerald-50/50 text-emerald-800 uppercase text-xs font-black tracking-widest border-b border-emerald-100">
                                <tr>
                                    <th class="px-8 py-5">Tanggal</th>
                                    <th class="px-8 py-5">Jam Masuk</th>
                                    <th class="px-8 py-5">Jam Keluar</th>
                                    <th class="px-8 py-5">Total Jam</th>
                                    <th class="px-8 py-5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-50">
                                @forelse($riwayatKehadiran as $Kehadiran)
                                <tr class="hover:bg-emerald-50/40 transition-colors group">
                                    <td class="px-8 py-5 font-bold text-gray-700">
                                        {{ \Carbon\Carbon::parse($Kehadiran->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-emerald-600 font-black px-3 py-1 bg-emerald-50 rounded-lg">
                                            {{ $Kehadiran->jam_masuk ?? '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-orange-600 font-black px-3 py-1 bg-orange-50 rounded-lg">
                                            {{ $Kehadiran->jam_pulang ?? '--:--' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 font-medium text-gray-600">
                                        @if($Kehadiran->jam_masuk && $Kehadiran->jam_pulang)
                                            @php
                                                $masuk = \Carbon\Carbon::parse($Kehadiran->jam_masuk);
                                                $pulang = \Carbon\Carbon::parse($Kehadiran->jam_pulang);
                                                $diff = $masuk->diff($pulang);
                                            @endphp
                                            {{ $diff->h }}j {{ $diff->i }}m
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php
                                            $statusRaw = trim($Kehadiran->status);
                                            $statusLabel = match($statusRaw) {
                                                'Hadir' => 'Hadir',
                                                'Hadir (Terlambat)', 'Terlambat' => 'Hadir (Terlambat)',
                                                'Izin' => 'Izin',
                                                'Sakit' => 'Sakit',
                                                default => 'Alpa',
                                            };
                                            $colorClass = match($statusRaw) {
                                                'Hadir' => 'bg-emerald-500',
                                                'Hadir (Terlambat)', 'Terlambat' => 'bg-amber-500',
                                                'Izin', 'Sakit' => 'bg-blue-500',
                                                default => 'bg-rose-500',
                                            };
                                            if (empty($statusRaw) && !empty($Kehadiran->jam_masuk)) {
                                                $statusLabel = 'Hadir';
                                                $colorClass = 'bg-emerald-500';
                                            }
                                        @endphp
                                        <span class="px-4 py-1.5 {{ $colorClass }} text-white rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-10 text-center text-gray-400 font-medium">Belum ada data kehadiran untuk periode ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tab Pengajuan --}}
            <div x-show="tab === 'pengajuan'" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                @forelse($riwayatPengajuan as $pengajuan)
                @php
                    // Logika Highlight Update HRD
                    $isNewUpdate = auth()->user()->unreadNotifications
                        ->where('data.kode_pengajuan', $pengajuan->kode_pengajuan)
                        ->count() > 0;

                    // Logika Gaya Card & Animasi
                    $cardStyle = match($pengajuan->status) {
                        'Disetujui' => 'border-emerald-200 shadow-emerald-100 ring-4 ring-emerald-500/5',
                        'Ditolak' => 'border-rose-100 opacity-80 grayscale-[0.3]',
                        'Pending' => 'border-blue-100 animate-pulse-slow',
                        default => 'border-emerald-100'
                    };

                    // Override style jika ada update baru
                    if($isNewUpdate) {
                        $cardStyle = 'border-amber-400 ring-4 ring-amber-400/20 animate-highlight-glow';
                    }

                    $iconBg = match($pengajuan->status) {
                        'Disetujui' => 'bg-emerald-500 text-white shadow-lg shadow-emerald-200',
                        'Ditolak' => 'bg-rose-500 text-white',
                        'Pending' => 'bg-blue-500 text-white',
                        default => 'bg-emerald-100 text-emerald-600'
                    };
                @endphp

                <div class="group bg-white/80 backdrop-blur-md p-8 rounded-[35px] border-2 {{ $cardStyle }} shadow-xl relative overflow-hidden transition-all duration-500 hover:shadow-2xl hover:border-emerald-400 hover:-translate-y-2">
                    
                    {{-- Badge Highlight "Update Baru" --}}
                    @if($isNewUpdate)
                        <div class="absolute -left-12 top-6 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] font-black uppercase tracking-widest py-1.5 w-44 -rotate-45 text-center shadow-lg z-20 border-b border-white/20">
                            Update Baru
                        </div>
                    @endif

                    {{-- Decorative Glow for Approved --}}
                    @if($pengajuan->status === 'Disetujui' && !$isNewUpdate)
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-400/10 blur-3xl rounded-full"></div>
                    @endif

                    <div class="absolute top-0 right-0 p-6">
                        @php
                            $badgeColor = match($pengajuan->status) {
                                'Disetujui' => 'bg-emerald-500',
                                'Ditolak' => 'bg-rose-500',
                                'Pending' => 'bg-blue-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <span class="px-4 py-1.5 {{ $badgeColor }} text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-md flex items-center gap-1.5">
                            @if($pengajuan->status === 'Pending')
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            @endif
                            {{ $pengajuan->status }}
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-14 h-14 {{ $isNewUpdate ? 'bg-amber-500 text-white shadow-amber-200' : $iconBg }} rounded-2xl flex items-center justify-center transition-transform group-hover:rotate-12 duration-500 shadow-lg">
                            @if($isNewUpdate)
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @elseif($pengajuan->status === 'Disetujui')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @elseif($pengajuan->status === 'Ditolak')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-black text-xl text-gray-800">{{ $pengajuan->jenis_pengajuan }}</h4>
                            <p class="text-xs {{ $isNewUpdate ? 'text-amber-600' : 'text-emerald-600' }} font-bold uppercase tracking-widest">#{{ $pengajuan->kode_pengajuan }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 {{ $isNewUpdate ? 'bg-amber-50/50 border-amber-100' : 'bg-emerald-50/30 border-emerald-50/50' }} p-5 rounded-2xl border">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Durasi:</span>
                            <span class="font-black text-gray-800">
                                {{ \Carbon\Carbon::parse($pengajuan->tgl_mulai)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($pengajuan->tgl_selesai)->translatedFormat('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm pt-3 border-t border-gray-100">
                            <span class="text-gray-500 font-medium">Alasan:</span>
                            <span class="italic font-bold text-gray-700 truncate ml-4">{{ $pengajuan->alasan }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-4 px-1">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium">Diperbarui {{ $pengajuan->updated_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-[10px] text-gray-400">
                            {{ $pengajuan->created_at->translatedFormat('d/m/y H:i') }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 bg-white/50 backdrop-blur-sm rounded-[35px] border-2 border-dashed border-emerald-200 flex flex-col items-center">
                    <p class="text-gray-400 font-bold">Tidak ada data pengajuan ditemukan.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>

    {{-- Custom Styles for Animations --}}
    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(0.995); }
        }
        @keyframes highlight-glow {
            0%, 100% { border-color: #fbbf24; box-shadow: 0 0 20px rgba(251, 191, 36, 0.1); }
            50% { border-color: #f59e0b; box-shadow: 0 0 30px rgba(251, 191, 36, 0.3); }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .animate-highlight-glow {
            animation: highlight-glow 2s ease-in-out infinite;
        }
    </style>
</x-app-layout>