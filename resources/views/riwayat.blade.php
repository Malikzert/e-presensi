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
                        class="px-8 py-2.5 rounded-xl font-bold transition-all duration-300 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Status Pengajuan
                    </button>
                </div>
            </div>

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
                                <tr class="hover:bg-emerald-50/40 transition-colors group">
                                    <td class="px-8 py-5 font-bold text-gray-700">07 Jan 2026</td>
                                    <td class="px-8 py-5">
                                        <span class="text-emerald-600 font-black px-3 py-1 bg-emerald-50 rounded-lg">07:30:12</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-orange-600 font-black px-3 py-1 bg-orange-50 rounded-lg">16:05:44</span>
                                    </td>
                                    <td class="px-8 py-5 font-medium text-gray-600">8j 35m</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm">Tepat Waktu</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-emerald-50/40 transition-colors group">
                                    <td class="px-8 py-5 font-bold text-gray-700">06 Jan 2026</td>
                                    <td class="px-8 py-5">
                                        <span class="text-rose-600 font-black px-3 py-1 bg-rose-50 rounded-lg">07:55:00</span>
                                    </td>
                                    <td class="px-8 py-5 text-gray-400 font-medium">16:00:10</td>
                                    <td class="px-8 py-5 font-medium text-gray-600">8j 5m</td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-4 py-1.5 bg-rose-500 text-white rounded-full text-[10px] font-black uppercase tracking-tighter shadow-sm">Terlambat</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'pengajuan'" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="group bg-white/80 backdrop-blur-md p-8 rounded-[35px] border border-emerald-100 shadow-xl relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:border-emerald-400 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="px-4 py-1.5 bg-blue-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-md">Diproses</span>
                    </div>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 transition-transform group-hover:rotate-12">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-xl text-gray-800">Cuti Tahunan</h4>
                            <p class="text-xs text-emerald-600 font-bold">Ref: #CT-2026001</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 bg-emerald-50/50 p-5 rounded-2xl border border-emerald-50">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Durasi:</span>
                            <span class="font-black text-emerald-800">3 Hari (12 Jan - 14 Jan)</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pt-3 border-t border-emerald-100/50">
                            <span class="text-gray-500 font-medium">Alasan:</span>
                            <span class="italic font-bold text-gray-700">Urusan Keluarga</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 text-right">Diajukan pada 05 Jan 2026 • 10:15 WIB</p>
                </div>

                <div class="group bg-white/80 backdrop-blur-md p-8 rounded-[35px] border border-emerald-100 shadow-xl relative overflow-hidden transition-all duration-300 hover:shadow-2xl hover:border-emerald-400 hover:-translate-y-1">
                    <div class="absolute top-0 right-0 p-6">
                        <span class="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-md">Disetujui</span>
                    </div>
                    
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 transition-transform group-hover:rotate-12">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-black text-xl text-gray-800">Izin Sakit</h4>
                            <p class="text-xs text-orange-600 font-bold">Ref: #SK-2026004</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 bg-orange-50/30 p-5 rounded-2xl border border-orange-50">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Durasi:</span>
                            <span class="font-black text-orange-800">1 Hari (02 Jan)</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pt-3 border-t border-orange-100/50">
                            <span class="text-gray-500 font-medium">Lampiran:</span>
                            <span class="font-black text-emerald-600 flex items-center gap-1 uppercase text-[10px]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                                Surat Dokter.pdf
                            </span>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 text-right">Diajukan pada 01 Jan 2026 • 08:00 WIB</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>