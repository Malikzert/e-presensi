<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Memastikan container peta terlihat */
        #map-container, #map-edit-container { min-height: 160px; width: 100%; }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" x-data="{ 
        openAdd: false, 
        openEdit: false, 
        openDelete: false,
        deleteRoute: '',
        currentData: {},
        selectedShiftMasuk: '',
        
        // KONFIGURASI GPS (RSU ANNA MEDIKA MADURA)
        map: null,
        editMap: null, 
        marker: null,
        editMarker: null,
        userLat: '', 
        userLng: '',
        rsLat: -7.1251369, 
        rsLng: 112.7250561, 
        maxRadius: 100000, 
        isOutOfRange: true,
        gpsLoaded: false,

        initMap(type = 'add') {
            this.gpsLoaded = false;
            setTimeout(() => {
                const containerId = type === 'add' ? 'map-container' : 'map-edit-container';
                let lat = this.rsLat;
                let lng = this.rsLng;

                if (type === 'edit' && this.currentData.lokasi_masuk) {
                    const parts = this.currentData.lokasi_masuk.split(',');
                    lat = parseFloat(parts[0]);
                    lng = parseFloat(parts[1]);
                }

                if (type === 'add') {
                    if (!this.map) {
                        this.map = L.map(containerId).setView([lat, lng], 17);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.map);
                        L.circle([this.rsLat, this.rsLng], { color: '#10b981', radius: this.maxRadius }).addTo(this.map);
                        this.marker = L.marker([lat, lng]).addTo(this.map);
                    } else {
                        this.map.invalidateSize();
                    }
                } else {
                    if (!this.editMap) {
                        this.editMap = L.map(containerId).setView([lat, lng], 17);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.editMap);
                        L.circle([this.rsLat, this.rsLng], { color: '#fbbf24', radius: this.maxRadius }).addTo(this.editMap);
                        this.editMarker = L.marker([lat, lng]).addTo(this.editMap);
                    } else {
                        this.editMap.setView([lat, lng], 17);
                        this.editMarker.setLatLng([lat, lng]);
                        this.editMap.invalidateSize();
                    }
                }
                this.updateLocation(type);
            }, 500);
        },

        updateLocation(type) {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung browser Anda');
                return;
            }
            navigator.geolocation.getCurrentPosition((position) => {
                this.userLat = position.coords.latitude;
                this.userLng = position.coords.longitude;
                this.gpsLoaded = true;
                
                const targetMap = type === 'add' ? this.map : this.editMap;
                const targetMarker = type === 'add' ? this.marker : this.editMarker;

                if (targetMarker) targetMarker.setLatLng([this.userLat, this.userLng]);
                if (targetMap) {
                    targetMap.setView([this.userLat, this.userLng], 17);
                    targetMap.invalidateSize();
                }

                const dist = this.getDistance(this.userLat, this.userLng, this.rsLat, this.rsLng);
                this.isOutOfRange = dist > this.maxRadius;
            }, (err) => {
                console.error(err);
                alert('Gagal mendapatkan lokasi GPS. Pastikan izin lokasi aktif.');
            }, { enableHighAccuracy: true });
        },

        getDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        },

        updateJam(e) {
            const selected = e.target.options[e.target.selectedIndex];
            if(selected.dataset.masuk) {
                document.getElementById('add_jam_masuk').value = selected.dataset.masuk;
                document.getElementById('add_jam_pulang').value = selected.dataset.pulang;
                this.selectedShiftMasuk = selected.dataset.masuk;
                this.cekTerlambat();
            }
        },

        cekTerlambat() {
            const jamInput = document.getElementById('add_jam_masuk').value;
            const statusSelect = document.getElementById('add_status');
            if (this.selectedShiftMasuk && jamInput) {
                statusSelect.value = jamInput > this.selectedShiftMasuk ? 'Hadir (Terlambat)' : 'Hadir';
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('admin.navs', ['title' => 'Monitoring Kehadiran'])

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-600 text-white rounded-2xl shadow-lg font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-md rounded-[35px] border border-emerald-100 overflow-hidden shadow-2xl">
                <form action="{{ route('admin.kehadirans') }}" method="GET" class="p-6 bg-emerald-50/50 border-b border-emerald-100 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex flex-wrap gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." class="border-emerald-200 rounded-xl text-sm w-64 focus:ring-emerald-500">
                        <input type="date" name="date" value="{{ request('date') }}" class="border-emerald-200 rounded-xl text-sm focus:ring-emerald-500">
                        <button type="submit" class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-200 transition-all uppercase">Filter</button>
                        <button type="button" @click="openAdd = true; initMap('add');" class="bg-white border border-emerald-200 text-emerald-700 px-6 py-2 rounded-xl text-xs font-black shadow-sm hover:bg-emerald-50 transition-all">
                            + TAMBAH MANUAL
                        </button>
                    </div>
                    <a href="{{ route('admin.export.kehadiran', request()->query()) }}" class="bg-emerald-600 text-white px-6 py-2 rounded-xl text-xs font-black shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Export Report</a>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Karyawan</th>
                                <th class="px-8 py-5 text-center">Info Jaringan (IP)</th>
                                <th class="px-8 py-5 text-center">Masuk</th>
                                <th class="px-8 py-5 text-center">Pulang</th>
                                <th class="px-8 py-5 text-center">Status</th>
                                <th class="px-8 py-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50">
                            @forelse($kehadirans as $item)
                            <tr class="hover:bg-emerald-50/50 transition-all">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->user->foto ? asset('images/users/'.$item->user->foto) : asset('images/users/default.jpg') }}" class="w-8 h-8 rounded-full object-cover border-2 border-emerald-100">
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $item->user->name ?? 'User Dihapus' }}</div>
                                            <div class="text-[9px] text-emerald-600 font-bold uppercase tracking-tight">ID: {{ $item->user->nopeg ?? '-' }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex flex-col gap-1 items-center">
                                        <span class="text-[9px] font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-500" title="IP Masuk">M: {{ $item->ip_address_masuk ?? 'N/A' }}</span>
                                        <span class="text-[9px] font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-500" title="IP Pulang">P: {{ $item->ip_address_pulang ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center text-emerald-600 font-black">
                                    {{ $item->jam_masuk ?? '--:--' }}
                                    <div class="text-[8px] text-gray-400 font-normal uppercase">{{ $item->lokasi_masuk ? 'GPS Locked' : 'No GPS' }}</div>
                                </td>
                                <td class="px-8 py-5 text-center text-rose-500 font-black">
                                    {{ $item->jam_pulang ?? '--:--' }}
                                    <div class="text-[8px] text-gray-400 font-normal uppercase">{{ $item->lokasi_pulang ? 'GPS Locked' : 'No GPS' }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ Str::contains($item->status, 'Hadir') ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="currentData = {{ json_encode($item->load('user')) }}; openEdit = true; initMap('edit');" class="p-2 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button @click="openDelete = true; deleteRoute = '{{ route('admin.kehadirans.destroy', $item->id) }}'; currentData = { name: '{{ $item->user->name ?? 'User' }}' };" class="p-2 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-10 text-center text-gray-400 italic text-sm">Tidak ada data kehadiran ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-emerald-50/30 border-t border-emerald-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-[10px] font-black uppercase text-emerald-700 tracking-widest">
                            Menampilkan {{ $kehadirans->firstItem() ?? 0 }} - {{ $kehadirans->lastItem() ?? 0 }} 
                            dari {{ $kehadirans->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            @if ($kehadirans->onFirstPage())
                                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase cursor-not-allowed">Prev</span>
                            @else
                                <a href="{{ $kehadirans->previousPageUrl() }}" class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-xl text-[10px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all">Prev</a>
                            @endif

                            <span class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-[10px] font-black">
                                {{ $kehadirans->currentPage() }}
                            </span>

                            @if ($kehadirans->hasMorePages())
                                <a href="{{ $kehadirans->nextPageUrl() }}" class="px-4 py-2 bg-white border border-emerald-200 text-emerald-700 rounded-xl text-[10px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all">Next</a>
                            @else
                                <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="openAdd" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[35px] w-full max-w-md p-8 shadow-2xl max-h-[90vh] overflow-y-auto border border-emerald-100">
                <h3 class="font-black text-emerald-900 uppercase text-xs tracking-widest mb-6 text-center">Tambah Kehadiran Manual</h3>
                <div class="mb-4">
                    <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Verifikasi Lokasi GPS</label>
                    <div id="map-container" class="rounded-2xl border border-emerald-100 bg-gray-50"></div>
                    <p x-show="isOutOfRange && gpsLoaded" class="text-[9px] text-rose-500 font-bold mt-2 ml-2 uppercase">Lokasi Anda di luar jangkauan RS!</p>
                </div>
                <form action="{{ route('admin.kehadirans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lokasi_masuk" :value="userLat + ',' + userLng">
                    <input type="hidden" name="lokasi_pulang" :value="userLat + ',' + userLng">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Pilih Karyawan</label>
                            <select name="user_id" class="w-full rounded-2xl border-emerald-100 text-sm focus:ring-emerald-500" required>
                                <option value="" selected disabled>Cari Karyawan...</option>
                                @foreach($users as $user) <option value="{{ $user->id }}">{{ $user->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Pilih Shift</label>
                            <select name="shift_id" @change="updateJam($event)" class="w-full rounded-2xl border-emerald-100 text-sm" required>
                                <option value="" selected disabled>Pilih Shift...</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" data-masuk="{{ $shift->jam_masuk }}" data-pulang="{{ $shift->jam_pulang }}">
                                        {{ $shift->nama_shift }} ({{ $shift->jam_masuk }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border-emerald-100 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Status</label>
                                <select name="status" id="add_status" class="w-full rounded-2xl border-emerald-100 text-sm">
                                    <option value="Hadir">Hadir</option>
                                    <option value="Hadir (Terlambat)">Hadir (Terlambat)</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Jam Masuk</label>
                                <input type="time" name="jam_masuk" id="add_jam_masuk" @input="cekTerlambat()" class="w-full rounded-2xl border-emerald-100 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Jam Pulang</label>
                                <input type="time" name="jam_pulang" id="add_jam_pulang" class="w-full rounded-2xl border-emerald-100 text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openAdd = false" class="flex-1 py-4 text-xs font-black uppercase text-gray-400">Batal</button>
                        <button type="submit" 
                                :disabled="isOutOfRange || !gpsLoaded" 
                                :class="(isOutOfRange || !gpsLoaded) ? 'bg-gray-300 cursor-not-allowed opacity-50' : 'bg-emerald-600 shadow-emerald-200'" 
                                class="flex-1 py-4 text-white rounded-2xl text-xs font-black uppercase shadow-lg transition-all">
                            <span x-text="!gpsLoaded ? 'Memuat GPS...' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="openEdit" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[35px] w-full max-w-md p-8 shadow-2xl max-h-[90vh] overflow-y-auto border border-emerald-100">
                <h3 class="font-black text-emerald-900 uppercase text-xs tracking-widest mb-6 text-center">Update Data & Lokasi</h3>
                <div class="mb-4">
                    <label class="block text-[10px] font-black uppercase text-amber-700 mb-2 ml-2">Verifikasi Lokasi (GPS Saat Ini)</label>
                    <div id="map-edit-container" class="rounded-2xl border border-amber-100 bg-gray-50"></div>
                </div>
                <form :action="'{{ url('admin/kehadirans') }}/' + currentData.id" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="lokasi_masuk" :value="currentData.lokasi_masuk">
                    <input type="hidden" name="lokasi_pulang" :value="userLat + ',' + userLng">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2">Karyawan</label>
                            <input type="text" :value="currentData.user ? currentData.user.name : ''" class="w-full rounded-2xl border-emerald-100 bg-gray-50 text-sm font-bold text-gray-500" readonly>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2 text-left">Masuk</label>
                                <input type="time" name="jam_masuk" x-model="currentData.jam_masuk" class="w-full rounded-2xl border-emerald-100 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2 text-left">Pulang</label>
                                <input type="time" name="jam_pulang" x-model="currentData.jam_pulang" class="w-full rounded-2xl border-emerald-100 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-2 text-left">Status</label>
                            <select name="status" x-model="currentData.status" class="w-full rounded-2xl border-emerald-100 text-sm">
                                <option value="Hadir">Hadir</option>
                                <option value="Hadir (Terlambat)">Hadir (Terlambat)</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                             <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Audit Log IP</p>
                             <div class="flex justify-between text-[10px] font-mono text-gray-500">
                                 <span>M: <span x-text="currentData.ip_address_masuk || 'N/A'"></span></span>
                                 <span>P: <span x-text="currentData.ip_address_pulang || 'N/A'"></span></span>
                             </div>
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="openEdit = false" class="flex-1 py-4 text-xs font-black uppercase text-gray-400">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-amber-500 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-amber-200">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL DELETE --}}
        <div x-show="openDelete" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-[35px] w-full max-w-sm p-8 shadow-2xl text-center border border-rose-100">
                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="font-black text-gray-900 uppercase text-sm mb-2">Hapus Data Kehadiran?</h3>
                <p class="text-gray-500 text-xs mb-8">Data milik <span class="font-bold text-rose-600" x-text="currentData.name"></span> tidak dapat dikembalikan.</p>
                <form :action="deleteRoute" method="POST">
                    @csrf @method('DELETE')
                    <div class="flex gap-3">
                        <button type="button" @click="openDelete = false" class="flex-1 py-4 text-xs font-black uppercase text-gray-400">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-rose-600 text-white rounded-2xl text-xs font-black uppercase shadow-lg shadow-rose-200">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>