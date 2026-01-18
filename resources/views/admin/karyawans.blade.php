<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" 
         x-data="{ 
            openModal: false, 
            editMode: false, 
            deleteModal: false, 
            deleteAction: '', 
            currentKaryawan: { name: '', nik: '', jabatan: '', email: '', foto: '' } 
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Data Karyawan'])

            <div class="flex justify-end mb-6">
                <button @click="openModal = true; editMode = false; currentKaryawan = {name:'', nik:'', jabatan:'', email:'', foto: 'default.jpg'}" 
                        class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-sm shadow-xl shadow-emerald-200 flex items-center gap-2 hover:bg-emerald-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Karyawan Baru
                </button>
            </div>

            <div class="mb-8">
                <form action="{{ route('admin.karyawans') }}" method="GET" class="relative max-w-md">
                    <input type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari Nama, NIK, atau Jabatan..." 
                        class="w-full bg-white/80 backdrop-blur-md border border-emerald-100 rounded-2xl py-3 px-5 pl-12 text-sm focus:ring-emerald-500 focus:border-emerald-500 shadow-lg shadow-emerald-900/5 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('admin.karyawans') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($karyawans as $karyawan)
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-[35px] border border-emerald-100 shadow-xl relative overflow-hidden group hover:border-emerald-400 transition-all">
                    <div class="flex items-center gap-4">
                        <img src="{{ $karyawan->foto ? asset('images/users/'.$karyawan->foto) : asset('images/users/default.jpg') }}" 
                             class="w-16 h-16 rounded-2xl shadow-md border-2 border-white object-cover" 
                             onerror="this.src='{{ asset('images/users/default.jpg') }}'">
                        
                        <div>
                            <h4 class="font-black text-gray-800">{{ $karyawan->name }}</h4>
                            <p class="text-xs text-emerald-600 font-bold uppercase">{{ $karyawan->jabatan ?? 'Staff RSU' }}</p>
                            <p class="text-[9px] text-gray-400 font-bold">{{ $karyawan->email }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-emerald-50 flex justify-between items-center text-[10px] font-bold text-gray-400">
                        <span>NIK: {{ $karyawan->nik ?? '-' }}</span>
                        <div class="flex gap-3">
                            <button @click="openModal = true; editMode = true; currentKaryawan = {{ json_encode($karyawan) }}" 
                                    class="text-emerald-600 hover:text-emerald-800 transition-colors uppercase tracking-widest">Edit</button>
                            
                            <button type="button" 
                                    @click="deleteModal = true; deleteAction = '{{ route('admin.karyawans.destroy', $karyawan->id) }}'" 
                                    class="text-rose-600 hover:text-rose-800 transition-colors uppercase tracking-widest">Hapus</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20 bg-white/50 rounded-[35px] border-2 border-dashed border-emerald-200">
                    <p class="text-emerald-800 font-bold">Belum ada data karyawan.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $karyawans->appends(request()->query())->links() }}
            </div>
        </div>

        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-emerald-900/40 backdrop-blur-sm">
            <div @click.away="openModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-md shadow-2xl border border-emerald-100 overflow-y-auto max-h-[90vh]">
                <h3 class="text-2xl font-black text-emerald-900 mb-6" x-text="editMode ? 'Edit Karyawan' : 'Tambah Karyawan'"></h3>
                
                <form :action="editMode ? `/admin/karyawans/${currentKaryawan.id}` : '{{ route('admin.karyawans.store') }}'" 
                      method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentKaryawan.name" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3 text-sm focus:ring-emerald-500 shadow-inner" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">NIK</label>
                            <input type="text" name="nik" x-model="currentKaryawan.nik" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3 text-sm focus:ring-emerald-500 shadow-inner" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Jabatan</label>
                            <input type="text" name="jabatan" x-model="currentKaryawan.jabatan" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3 text-sm focus:ring-emerald-500 shadow-inner" required>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Email</label>
                        <input type="email" name="email" x-model="currentKaryawan.email" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3 text-sm focus:ring-emerald-500 shadow-inner" required>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Password <span x-show="editMode" class="lowercase font-normal text-gray-400">(Kosongkan jika tidak ganti)</span></label>
                        <input type="password" name="password" :required="!editMode" class="w-full rounded-2xl border-emerald-100 bg-emerald-50/50 p-3 text-sm focus:ring-emerald-500 shadow-inner">
                    </div>

                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <img :src="currentKaryawan.foto ? `/images/users/${currentKaryawan.foto}` : '/images/users/default.jpg'" 
                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-emerald-100 shadow-sm"
                                 onerror="this.src='/images/users/default.jpg'">
                            <div class="flex-1">
                                <label class="text-[10px] font-black uppercase text-emerald-600 ml-2">Foto Karyawan</label>
                                <input type="file" name="foto" class="w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="openModal = false" class="flex-1 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-2xl font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="deleteModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-rose-900/20 backdrop-blur-sm">
            <div @click.away="deleteModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-sm shadow-2xl border border-rose-100 text-center">
                <div class="w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Hapus Karyawan?</h3>
                <p class="text-sm text-gray-500 mb-8">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3">
                    <button type="button" @click="deleteModal = false" class="flex-1 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Batal</button>
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-2xl font-black shadow-lg shadow-rose-200">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>