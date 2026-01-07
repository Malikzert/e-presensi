<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @include('admin.navs', ['title' => 'Kelola Pengajuans'])

            <div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-black shadow-md shadow-emerald-200">Semua</button>
                    <button class="px-4 py-2 bg-white/70 backdrop-blur-md text-emerald-700 rounded-xl text-xs font-bold border border-emerald-100 hover:bg-emerald-50 transition-all">Pending</button>
                    <button class="px-4 py-2 bg-white/70 backdrop-blur-md text-emerald-700 rounded-xl text-xs font-bold border border-emerald-100 hover:bg-emerald-50 transition-all">Disetujui</button>
                </div>
                
                <div class="relative group">
                    <input type="text" placeholder="Cari nama karyawan..." 
                        class="pl-10 pr-4 py-2.5 bg-white/80 backdrop-blur-md border-emerald-100 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm w-64 transition-all group-hover:w-80 shadow-sm">
                    <div class="absolute left-3 top-3 text-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-md shadow-2xl rounded-[35px] border border-emerald-100 overflow-hidden transition-all hover:shadow-emerald-900/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-emerald-50/50 text-emerald-900 uppercase text-[10px] font-black tracking-widest border-b border-emerald-100">
                            <tr>
                                <th class="px-8 py-5 text-center w-20">Foto</th>
                                <th class="px-8 py-5">Karyawan & Unit</th>
                                <th class="px-8 py-5 text-center">Tipe Izin</th>
                                <th class="px-8 py-5">Periode</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50/50">
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-8 py-5">
                                    <img src="https://ui-avatars.com/api/?name=Siska+Perawat&background=10b981&color=fff" class="w-12 h-12 rounded-2xl shadow-sm border border-white mx-auto">
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-black text-emerald-900 text-sm">Siska Amelia, S.Kep</div>
                                    <div class="text-[10px] text-emerald-600 font-bold uppercase tracking-tight">Perawat Rawat Inap</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black uppercase">Cuti Tahunan</span>
                                </td>
                                <td class="px-8 py-5 text-sm">
                                    <div class="font-bold text-gray-700">3 Hari</div>
                                    <div class="text-[10px] text-gray-400">12 Jan - 14 Jan 2026</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                                        <span class="text-[10px] font-black text-orange-600 uppercase">Pending</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center items-center gap-2">
                                        <button class="group relative p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Setujui">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                        <button class="group relative p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Tolak">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                        <button class="p-2.5 bg-gray-50 text-gray-500 rounded-xl hover:bg-gray-200 transition-all" title="Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-8 py-5 text-center">
                                    <img src="https://ui-avatars.com/api/?name=Dr+Andi&background=0284c7&color=fff" class="w-12 h-12 rounded-2xl shadow-sm border border-white mx-auto">
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-black text-emerald-900 text-sm">dr. Andi Hermawan</div>
                                    <div class="text-[10px] text-emerald-600 font-bold uppercase tracking-tight">Dokter Spesialis Anak</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-black uppercase">Izin Sakit</span>
                                </td>
                                <td class="px-8 py-5 text-sm">
                                    <div class="font-bold text-gray-700">1 Hari</div>
                                    <div class="text-[10px] text-gray-400">08 Jan 2026</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-tighter">Disetujui</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="text-xs font-bold text-gray-400 italic italic">Telah Diproses</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-emerald-50/30 border-t border-emerald-50 flex justify-between items-center text-xs font-bold text-emerald-700/50">
                    <p>Menampilkan 2 dari 12 pengajuan masuk</p>
                    <div class="flex gap-2">
                        <button class="p-2 bg-white rounded-lg border border-emerald-100 hover:text-emerald-600">Sebelumnya</button>
                        <button class="p-2 bg-white rounded-lg border border-emerald-100 hover:text-emerald-600">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>