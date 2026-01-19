<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    {{-- Container Utama dengan Alpine.js untuk Modal --}}
    <div class="relative z-10 py-12 min-h-screen" 
         x-data="{ 
            search: '{{ request('search') }}',
            showTambahModal: false,
            showEditModal: false,
            editData: { id: '', jenis: '', mulai: '', selesai: '', alasan: '', bukti: '' }
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @include('admin.navs', ['title' => 'Kelola Pengajuan'])

            <div class="mb-6 flex flex-wrap gap-4 items-center justify-between">
                <div class="flex gap-2">
                    <a href="{{ route('admin.pengajuans') }}" 
                       class="px-4 py-2 {{ !request('status') ? 'bg-emerald-600 text-white' : 'bg-white/70 text-emerald-700' }} rounded-xl text-xs font-black shadow-md shadow-emerald-200 transition-all">Semua</a>
                    
                    <a href="{{ route('admin.pengajuans', ['status' => 'Pending']) }}" 
                       class="px-4 py-2 {{ request('status') == 'Pending' ? 'bg-emerald-600 text-white' : 'bg-white/70 text-emerald-700' }} rounded-xl text-xs font-bold border border-emerald-100 hover:bg-emerald-50 transition-all">Pending</a>
                    
                    <a href="{{ route('admin.pengajuans', ['status' => 'Disetujui']) }}" 
                       class="px-4 py-2 {{ request('status') == 'Disetujui' ? 'bg-emerald-600 text-white' : 'bg-white/70 text-emerald-700' }} rounded-xl text-xs font-bold border border-emerald-100 hover:bg-emerald-50 transition-all">Disetujui</a>
                    <a href="{{ route('admin.pengajuans', ['status' => 'Ditolak']) }}" 
                       class="px-4 py-2 {{ request('status') == 'Ditolak' ? 'bg-emerald-600 text-white' : 'bg-white/70 text-emerald-700' }} rounded-xl text-xs font-bold border border-emerald-100 hover:bg-emerald-50 transition-all">Ditolak</a>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Form Export Kecil --}}
                    <form action="{{ route('admin.pengajuans.export') }}" method="GET" class="flex items-center gap-2 bg-white/50 p-1.5 rounded-2xl border border-emerald-100 shadow-sm">
                        <select name="bulan" class="bg-transparent border-none text-[10px] font-black text-emerald-700 focus:ring-0">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <select name="tahun" class="bg-transparent border-none text-[10px] font-black text-emerald-700 focus:ring-0">
                            @for($y=date('Y'); $y>=2023; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="p-2 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all" title="Export Excel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </button>
    </form>
                    {{-- Tombol Tambah --}}
                    <button @click="showTambahModal = true" class="px-5 py-2.5 bg-emerald-600 text-white rounded-2xl text-xs font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        TAMBAH
                    </button>

                    <form action="{{ route('admin.pengajuans') }}" method="GET" class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." 
                            class="pl-10 pr-4 py-2.5 bg-white/80 backdrop-blur-md border-emerald-100 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-sm w-64 transition-all group-hover:w-80 shadow-sm">
                        <div class="absolute left-3 top-3 text-emerald-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-rose-100 text-rose-700 rounded-2xl font-bold text-sm border border-rose-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-md shadow-2xl rounded-[35px] border border-emerald-100 overflow-hidden transition-all hover:shadow-emerald-900/10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-emerald-50/50 text-emerald-900 uppercase text-[10px] font-black tracking-widest border-b border-emerald-100">
                            <tr>
                                <th class="px-8 py-5 text-center w-20">Foto</th>
                                <th class="px-8 py-5">Karyawan</th>
                                <th class="px-8 py-5 text-center">Tipe</th>
                                <th class="px-8 py-5">Periode & Alasan</th>
                                <th class="px-8 py-5 text-center">Bukti</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50/50">
                            @forelse($pengajuans as $p)
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-8 py-5">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($p->user->name) }}&background=10b981&color=fff" class="w-12 h-12 rounded-2xl shadow-sm border border-white mx-auto">
                                </td>
                                <td class="px-8 py-5">
                                    <div class="font-black text-emerald-900 text-sm">{{ $p->user->name }}</div>
                                    <div class="text-[10px] text-emerald-600 font-bold uppercase tracking-tight">{{ $p->user->email }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $color = match($p->jenis_pengajuan) {
                                            'Cuti' => 'blue',
                                            'Sakit' => 'amber',
                                            default => 'purple'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 bg-{{ $color }}-50 text-{{ $color }}-700 rounded-lg text-[10px] font-black uppercase">
                                        {{ $p->jenis_pengajuan }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm">
                                    @php
                                        $durasi = $p->tgl_mulai->diffInDays($p->tgl_selesai) + 1;
                                    @endphp
                                    <div class="font-bold text-gray-700">{{ $durasi }} Hari</div>
                                    <div class="text-[10px] text-gray-400 font-medium">
                                        {{ $p->tgl_mulai->format('d M') }} - {{ $p->tgl_selesai->format('d M Y') }}
                                    </div>
                                    <div class="mt-1 text-[11px] text-emerald-800 italic line-clamp-1">"{{ $p->alasan }}"</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($p->bukti)
                                        <a href="{{ asset('uploads/bukti/' . $p->bukti) }}" target="_blank" class="inline-flex p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-[10px] text-gray-300 italic font-bold">No File</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-1.5">
                                        @if($p->status == 'Pending')
                                            <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                                            <span class="text-[10px] font-black text-orange-600 uppercase">Pending</span>
                                        @elseif($p->status == 'Disetujui')
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                            <span class="text-[10px] font-black text-emerald-600 uppercase">Disetujui</span>
                                        @else
                                            <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                            <span class="text-[10px] font-black text-rose-600 uppercase">Ditolak</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex justify-center items-center gap-2">
                                        @if($p->status == 'Pending')
                                            <form action="{{ route('admin.pengajuans.status', $p->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Disetujui">
                                                <button type="submit" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Setujui">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.pengajuans.status', $p->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="Ditolak">
                                                <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Tolak">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>

                                            {{-- Tombol Edit --}}
                                            <button @click="
                                                showEditModal = true; 
                                                editData = { 
                                                    id: '{{ $p->id }}', 
                                                    jenis: '{{ $p->jenis_pengajuan }}', 
                                                    mulai: '{{ $p->tgl_mulai->format('Y-m-d') }}', 
                                                    selesai: '{{ $p->tgl_selesai->format('Y-m-d') }}', 
                                                    alasan: '{{ $p->alasan }}' 
                                                }" 
                                                class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                        @else
                                            <div class="text-center flex items-center gap-2">
                                                <span class="text-[10px] font-bold text-gray-400 italic">Selesai Diproses</span>
                                                <form action="{{ route('admin.pengajuans.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data pengajuan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-rose-300 hover:text-rose-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-8 py-10 text-center text-gray-400 font-bold italic">Belum ada pengajuan masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 bg-emerald-50/30 border-t border-emerald-50 flex justify-between items-center text-xs font-bold text-emerald-700/50">
                    <p>Menampilkan {{ $pengajuans->firstItem() }} sampai {{ $pengajuans->lastItem() }} dari {{ $pengajuans->total() }} pengajuan</p>
                    <div class="flex gap-2">
                        {{ $pengajuans->links() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="showTambahModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="showTambahModal = false" class="bg-white w-full max-w-lg rounded-[35px] shadow-2xl overflow-hidden border border-emerald-100 animate-in fade-in zoom-in duration-300">
                <div class="p-8">
                    <h3 class="text-xl font-black text-emerald-900 mb-6 uppercase tracking-tight">Tambah Pengajuan</h3>
                    <form action="{{ route('admin.pengajuans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Pilih Karyawan</label>
                            <select name="user_id" required class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Jenis</label>
                                <select name="jenis_pengajuan" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                                    <option>Cuti</option>
                                    <option>Sakit</option>
                                    <option>Izin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Tgl Mulai</label>
                                <input type="date" name="tgl_mulai" required class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Tgl Selesai</label>
                                <input type="date" name="tgl_selesai" required class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Upload Bukti (Max 5MB)</label>
                                <input type="file" name="bukti" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Alasan</label>
                            <textarea name="alasan" required rows="2" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="showTambahModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl text-xs font-bold uppercase">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all uppercase">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-[35px] shadow-2xl overflow-hidden border border-emerald-100">
                <div class="p-8">
                    <h3 class="text-xl font-black text-amber-900 mb-6 uppercase tracking-tight">Edit Pengajuan</h3>
                    <form :action="'{{ url('admin/pengajuans') }}/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Jenis</label>
                                <select name="jenis_pengajuan" x-model="editData.jenis" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                                    <option>Cuti</option>
                                    <option>Sakit</option>
                                    <option>Izin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Tgl Mulai</label>
                                <input type="date" name="tgl_mulai" x-model="editData.mulai" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Tgl Selesai</label>
                                <input type="date" name="tgl_selesai" x-model="editData.selesai" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Update Bukti (Max 5MB)</label>
                                <input type="file" name="bukti" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-emerald-700 uppercase mb-1">Alasan</label>
                            <textarea name="alasan" x-model="editData.alasan" rows="3" class="w-full bg-emerald-50 border-none rounded-xl text-sm focus:ring-emerald-500"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl text-xs font-bold uppercase">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-amber-500 text-white rounded-xl text-xs font-black uppercase shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>