<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-10">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-50/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" 
         x-data="{ 
            tab: 'jabatan', 
            openModal: false, 
            editMode: false, 
            type: '', 
            form: { id: '', nama: '', kode: '' } 
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Master Unit & Jabatan'])

            <div class="flex gap-4 mb-8 bg-white/50 p-2 rounded-3xl w-fit border border-emerald-100 shadow-sm backdrop-blur-md">
                <button @click="tab = 'jabatan'" :class="tab === 'jabatan' ? 'bg-emerald-600 text-white' : 'text-emerald-600'" class="px-8 py-3 rounded-2xl font-black text-xs transition-all">JABATAN</button>
                <button @click="tab = 'unit'" :class="tab === 'unit' ? 'bg-emerald-600 text-white' : 'text-emerald-600'" class="px-8 py-3 rounded-2xl font-black text-xs transition-all">UNIT KERJA</button>
            </div>

            <div x-show="tab === 'jabatan'" class="bg-white/80 backdrop-blur-md rounded-[40px] border border-emerald-100 shadow-2xl overflow-hidden">
                <div class="p-8 flex justify-between items-center border-b border-emerald-50">
                    <h3 class="font-black text-emerald-900">Daftar Jabatan</h3>
                    <button @click="openModal = true; editMode = false; type = 'jabatan'; form = {nama:''}" class="bg-emerald-600 text-white px-6 py-2 rounded-xl font-bold text-xs hover:bg-emerald-700 transition-all">+ Jabatan</button>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-emerald-50/50 text-emerald-700 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Nama Jabatan</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @foreach($jabatans as $j)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <td class="px-8 py-4 font-bold text-gray-700">{{ $j->nama_jabatan }}</td>
                            <td class="px-8 py-4 flex justify-end gap-4">
                                <button @click="openModal=true; editMode=true; type='jabatan'; form={id:'{{$j->id}}', nama:'{{$j->nama_jabatan}}'}" class="text-emerald-600 font-bold text-[10px] uppercase">Edit</button>
                                <form action="{{ route('admin.jabatans.destroy', $j->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold text-[10px] uppercase" onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-show="tab === 'unit'" x-cloak class="bg-white/80 backdrop-blur-md rounded-[40px] border border-emerald-100 shadow-2xl overflow-hidden">
                <div class="p-8 flex justify-between items-center border-b border-emerald-50">
                    <h3 class="font-black text-emerald-900">Daftar Unit Kerja</h3>
                    <button @click="openModal = true; editMode = false; type = 'unit'; form = {id:'', kode:'', nama:''}" class="bg-emerald-600 text-white px-6 py-2 rounded-xl font-bold text-xs hover:bg-emerald-700 transition-all">+ Unit</button>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-emerald-50/50 text-emerald-700 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Kode Unit</th>
                            <th class="px-8 py-4">Nama Unit</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-50">
                        @foreach($units as $u)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <td class="px-8 py-4 font-mono font-bold text-emerald-600">{{ $u->kode_unit }}</td>
                            <td class="px-8 py-4 font-bold text-gray-700">{{ $u->nama_unit }}</td>
                            <td class="px-8 py-4 flex justify-end gap-4">
                                <button @click="openModal=true; editMode=true; type='unit'; form={id:'{{$u->id}}', kode:'{{$u->kode_unit}}', nama:'{{$u->nama_unit}}'}" class="text-emerald-600 font-bold text-[10px] uppercase">Edit</button>
                                <form action="{{ route('admin.units.destroy', $u->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-rose-600 font-bold text-[10px] uppercase" onclick="return confirm('Hapus Unit?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-emerald-900/40 backdrop-blur-sm">
            <div @click.away="openModal = false" class="bg-white rounded-[40px] p-8 w-full max-w-sm shadow-2xl">
                <h3 class="text-xl font-black text-emerald-900 mb-6" x-text="(editMode ? 'Edit ' : 'Tambah ') + type"></h3>
                
                <form :action="editMode ? `/admin/${type}s/${form.id}` : `/admin/${type}s`" method="POST">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="space-y-4">
                        <template x-if="type === 'unit'">
                            <div>
                                <label class="text-[10px] font-black text-emerald-600 ml-2">KODE UNIT</label>
                                <input type="text" name="kode_unit" x-model="form.kode" class="w-full rounded-2xl border-emerald-100 bg-emerald-50 p-3 text-sm focus:ring-emerald-500" required>
                            </div>
                        </template>
                        <div>
                            <label class="text-[10px] font-black text-emerald-600 ml-2" x-text="type === 'jabatan' ? 'NAMA JABATAN' : 'NAMA UNIT'"></label>
                            <input type="text" :name="type === 'jabatan' ? 'nama_jabatan' : 'nama_unit'" x-model="form.nama" class="w-full rounded-2xl border-emerald-100 bg-emerald-50 p-3 text-sm focus:ring-emerald-500" required>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button type="button" @click="openModal = false" class="flex-1 py-3 font-bold text-gray-400">Batal</button>
                        <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-2xl font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>