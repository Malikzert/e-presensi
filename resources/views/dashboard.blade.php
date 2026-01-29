<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-800 leading-tight relative z-10">
            {{ __('Dashboard Utama Pegawai') }}
        </h2>
    </x-slot>

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-10">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div x-data="{ 
            showModal: true, 
            timer: 2,
            init() {
                let interval = setInterval(() => {
                    if (this.timer > 0) this.timer--;
                    else { this.showModal = false; clearInterval(interval); }
                }, 1000);
            }
         }" 
         x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white/90 backdrop-blur-md rounded-[40px] shadow-2xl max-w-sm w-full p-8 text-center border border-white">
            <img src="{{ asset('images/logors.png') }}" alt="Logo" class="h-16 mx-auto mb-4 object-contain">
            <h3 class="text-2xl font-extrabold text-emerald-900 mb-2">Selamat Datang!</h3>
            <p class="text-gray-600 mb-6 text-sm">Halo, <span class="font-bold text-emerald-600">{{ Auth::user()->name }}</span>. Selamat bertugas di RSU Anna Medika.</p>
            <div class="relative inline-flex items-center justify-center">
                <svg class="w-16 h-16"><circle class="text-gray-200" stroke-width="5" stroke="currentColor" fill="transparent" r="28" cx="32" cy="32" /><circle class="text-emerald-500" stroke-width="5" stroke-linecap="round" stroke="currentColor" fill="transparent" r="28" cx="32" cy="32" stroke-dasharray="176" :stroke-dashoffset="176 - (176 * timer / 5)" /></svg>
                <span class="absolute text-xl font-bold text-emerald-600" x-text="timer"></span>
            </div>
        </div>
    </div>

    <div class="relative z-10 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="px-3 py-1 bg-emerald-600 text-white text-[10px] font-bold rounded-full uppercase tracking-widest">Live System</span>
                    <h1 class="text-3xl font-black text-gray-800 mt-2">Ringkasan Presensi</h1>
                </div>
                <div class="flex gap-3 bg-white/50 backdrop-blur-md p-2 rounded-2xl shadow-sm border border-white">
                    <a href="{{ route('presensi.pdf') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-red-600 rounded-xl text-xs font-bold shadow-sm hover:bg-red-50 transition border border-red-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"/></svg> PDF Report
                    </a>
                    <a href="{{ route('presensi.csv') }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export CSV
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" 
                 x-data="{ 
                    monthlyRate: {{ $monthlyRate }}, 
                    yearlyRate: {{ $yearlyRate }},
                    getColor(rate) {
                        if (rate <= 50) return 'bg-red-50 border-red-100 text-red-600';
                        if (rate <= 70) return 'bg-amber-50 border-amber-100 text-amber-600';
                        return 'bg-emerald-50 border-emerald-100 text-emerald-600';
                    },
                    getIconBg(rate) {
                        if (rate <= 50) return 'bg-red-500';
                        if (rate <= 70) return 'bg-amber-500';
                        return 'bg-emerald-500';
                    }
                 }">
                
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white shadow-xl">
                    <div class="p-3 w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Status Hari Ini</p>
                    <p class="text-xl font-black text-emerald-600">
                        {{ $presensiHariIni ? $presensiHariIni->status : 'Belum Hadir' }} 
                        <span class="text-[10px] font-normal text-gray-400">{{ $presensiHariIni ? $presensiHariIni->jam_masuk : '--:--' }} WIB</span>
                    </p>
                </div>

                <div :class="getColor(monthlyRate)" class="p-6 rounded-3xl border shadow-xl transition-all duration-500">
                    <div :class="getIconBg(monthlyRate)" class="p-3 w-12 h-12 text-white rounded-2xl mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-xs font-bold opacity-70 uppercase">Rata-rata Bulanan</p>
                    <p class="text-2xl font-black" x-text="monthlyRate + '%'"></p>
                </div>

                <div :class="getColor(yearlyRate)" class="p-6 rounded-3xl border shadow-xl transition-all duration-500">
                    <div :class="getIconBg(yearlyRate)" class="p-3 w-12 h-12 text-white rounded-2xl mb-4 shadow-lg">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                    <p class="text-xs font-bold opacity-70 uppercase">Performa Tahunan</p>
                    <p class="text-2xl font-black" x-text="yearlyRate + '%'"></p>
                    <p x-show="yearlyRate <= 50" class="text-[10px] mt-1 font-bold animate-pulse">⚠️ Perhatian: Kehadiran Rendah</p>
                </div>

                <div class="bg-white/80 backdrop-blur-md p-6 rounded-3xl border border-white shadow-xl">
                    <div class="p-3 w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z"></path></svg>
                    </div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Total Izin/Sakit</p>
                    <p class="text-xl font-black text-gray-800">
                        {{ $totalIzinSakit }} Hari 
                        <span class="text-[10px] font-normal text-gray-400">Tahun Ini</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white/90 backdrop-blur-md p-8 rounded-[40px] shadow-xl border border-white">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                        <div>
                            <h3 class="font-black text-gray-800 text-lg uppercase tracking-tight">Grafik Intensitas Kehadiran</h3>
                            <p class="text-xs text-gray-500">Monitor waktu kerja produktif Anda</p>
                        </div>
                        <select id="timeFilter" class="bg-emerald-50 border-none rounded-2xl text-xs font-bold text-emerald-700 focus:ring-emerald-500 cursor-pointer transition-all hover:bg-emerald-100">
                            <option value="weekly">Mingguan (7 Hari Terakhir)</option>
                            <option value="monthly" selected>Bulanan (6 Bln Terakhir)</option>
                            <option value="yearly">Tahunan (3 Thn Terakhir)</option>
                        </select>
                    </div>
                    <div class="relative h-[300px]">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <div class="bg-emerald-900 text-white p-8 rounded-[40px] shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                    </div>
                    <h3 class="font-black text-lg mb-6 uppercase tracking-widest border-b border-emerald-800 pb-4">Log Terakhir</h3>
                    <div class="space-y-8">
                        @forelse($logs as $log)
                        <div class="flex gap-4">
                            <div class="relative">
                                <div class="w-3 h-3 {{ $loop->first ? 'bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)]' : 'bg-white/20' }} rounded-full"></div>
                                @if(!$loop->last)
                                <div class="absolute top-3 left-1.5 w-[1px] h-10 bg-emerald-700"></div>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-emerald-300 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd') }}</p>
                                <p class="text-sm font-bold">{{ $log->status }}</p>
                                <p class="text-[10px] text-emerald-400">{{ $log->jam_masuk ?? '--:--' }} WIB</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-emerald-400">Belum ada riwayat aktivitas.</p>
                        @endforelse
                    </div>
                    
                    <a href="/riwayat" class="block text-center w-full mt-10 py-3 bg-white text-emerald-900 rounded-2xl font-black text-xs uppercase transition hover:bg-emerald-100">
                        Lihat Semua Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const allChartData = @json($chartData);

        // Inisialisasi Chart (Default: Monthly)
        const currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: allChartData.monthly.labels,
                datasets: [{
                    data: allChartData.monthly.data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(0,0,0,0.05)' }, 
                        ticks: { font: { size: 10 }, stepSize: 1 } 
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
                }
            }
        });

        // Logika Pergantian Data via Filter
        document.getElementById('timeFilter').addEventListener('change', function() {
            const selected = this.value; // weekly, monthly, atau yearly
            const newData = allChartData[selected];

            currentChart.data.labels = newData.labels;
            currentChart.data.datasets[0].data = newData.data;
            
            // Animasi update grafik
            currentChart.update();
        });
    </script>
</x-app-layout>