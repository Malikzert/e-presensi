<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Data Karyawans'])

            <div class="flex justify-end mb-6">
                <button class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-emerald-200 flex items-center gap-2 hover:bg-emerald-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Karyawan Baru
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-[35px] border border-emerald-100 shadow-xl relative overflow-hidden group hover:border-emerald-400 transition-all">
                    <div class="flex items-center gap-4">
                        <img src="https://ui-avatars.com/api/?name=Dewi+Persik" class="w-16 h-16 rounded-2xl shadow-md border-2 border-white">
                        <div>
                            <h4 class="font-black text-gray-800">Dewi Persik</h4>
                            <p class="text-xs text-emerald-600 font-bold uppercase">Kepala Perawat</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-emerald-50 flex justify-between items-center text-[10px] font-bold text-gray-400">
                        <span>NIK: 19920801022</span>
                        <div class="flex gap-2">
                            <button class="text-emerald-600 hover:underline">Edit</button>
                            <button class="text-rose-600 hover:underline">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>