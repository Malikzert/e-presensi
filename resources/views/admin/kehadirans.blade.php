<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Monitoring Kehadirans'])

            <div class="bg-white/80 backdrop-blur-md rounded-[35px] border border-emerald-100 overflow-hidden shadow-2xl">
                <div class="p-6 bg-emerald-50/50 border-b border-emerald-100 flex justify-between items-center">
                    <input type="date" class="border-emerald-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <button class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-xs font-black shadow-lg shadow-emerald-200">Export Report</button>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Karyawan</th>
                            <th class="px-8 py-5">Jam Masuk</th>
                            <th class="px-8 py-5">Jam Keluar</th>
                            <th class="px-8 py-5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        <tr class="hover:bg-emerald-50/50 transition-all">
                            <td class="px-8 py-5 font-bold text-gray-800">Ahmad Subarjo</td>
                            <td class="px-8 py-5 text-emerald-600 font-black">07:25:10</td>
                            <td class="px-8 py-5 text-gray-400 font-medium">--:--:--</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[9px] font-black uppercase">Tepat Waktu</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>