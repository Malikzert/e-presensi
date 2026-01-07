<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Admin Dashboards'])

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    $stats = [
                        ['label' => 'Total Karyawan', 'value' => '342', 'color' => 'emerald', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857...'],
                        ['label' => 'Hadir Hari Ini', 'value' => '318', 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10...'],
                        ['label' => 'Pending Izin', 'value' => '12', 'color' => 'orange', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0...'],
                        ['label' => 'Terlambat', 'value' => '7', 'color' => 'rose', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856...']
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-[30px] border border-emerald-100 shadow-xl shadow-emerald-900/5 hover:scale-105 transition-all">
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                    <h3 class="text-3xl font-black text-emerald-900">{{ $stat['value'] }}</h3>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white/80 backdrop-blur-md rounded-[35px] border border-emerald-100 p-8 shadow-xl">
                    <h3 class="font-black text-emerald-800 mb-6 flex items-center gap-2">
                        <span class="w-2 h-6 bg-emerald-500 rounded-full"></span> Aktivitas Terbaru
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-emerald-50/50 rounded-2xl border border-emerald-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-200 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Dr. Sarah Johnson</p>
                                    <p class="text-[10px] text-emerald-600 font-bold">Check-in: 07:15 WIB</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-black text-emerald-500 uppercase">Success</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>