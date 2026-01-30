<x-app-layout>
    {{-- Background Section --}}
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-500 text-white rounded-2xl shadow-lg font-bold flex items-center gap-3 animate-bounce">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-500 text-white rounded-2xl shadow-lg font-bold flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-emerald-800 tracking-tight">Form Pengajuan</h2>
                <p class="text-gray-600 font-medium">Silahkan lengkapi formulir di bawah untuk mengajukan izin atau cuti / tukar shift medis di RSU Anna Medika.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Kolom Kiri: Form --}}
                <div class="lg:col-span-2">
                    <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-[30px] border border-emerald-100 p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-200/50 hover:border-emerald-400">
                        
                        {{-- Form Action --}}
                        <form action="{{ route('pengajuan.user.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-emerald-600 transition-colors">Jenis Pengajuan</label>
                                    {{-- Pastikan name="kategori_pengajuan_id" agar sesuai dengan $fillable di Model --}}
                                    <select name="kategori_pengajuan_id" required class="w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 transition-all cursor-pointer">
                                        <option value="" disabled selected>Pilih Jenis Pengajuan</option>
                                        
                                        {{-- Gunakan variabel yang dikirim dari Route ($kategori_pengajuans) --}}
                                        @foreach($kategori_pengajuans as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->nama_pengajuan }} 
                                                @if(str_contains(strtolower($item->nama_pengajuan), 'sakit'))
                                                    (Wajib Surat Dokter)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="group">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-emerald-600 transition-colors">Tanggal Mulai s/d Selesai</label>
                                    <div class="flex items-center gap-2">
                                        {{-- Input Tanggal Mulai --}}
                                        <input type="date" 
                                            name="tgl_mulai" 
                                            id="tgl_mulai"
                                            required 
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                            class="w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 text-sm transition-all">
                                        
                                        <span class="text-gray-400 font-bold">-</span>
                                        
                                        {{-- Input Tanggal Selesai --}}
                                        <input type="date" 
                                            name="tgl_selesai" 
                                            id="tgl_selesai"
                                            required 
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                            class="w-full border-gray-200 rounded-xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 text-sm transition-all">
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-emerald-600 transition-colors">Alasan / Keterangan</label>
                                <textarea name="alasan" rows="4" required class="w-full border-gray-200 rounded-2xl focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 placeholder-gray-300 transition-all" placeholder="Jelaskan alasan pengajuan Anda secara detail..."></textarea>
                            </div>

                            <div class="bg-emerald-50/30 border-2 border-dashed border-emerald-200 rounded-2xl p-6 text-center transition-all hover:bg-emerald-50 hover:border-emerald-400 group cursor-pointer relative">
                                <label class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-10 h-10 text-emerald-500 mb-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-sm font-bold text-emerald-700">Upload Surat Dokter / Berkas Pendukung</span>
                                        <span class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF (Maks. 5MB)</span>
                                        
                                        {{-- Penampil Nama File --}}
                                        <span id="file-name-display" class="mt-3 text-xs font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-full hidden"></span>
                                    </div>
                                    <input type="file" name="bukti" id="bukti-input" class="hidden" accept=".jpg,.jpeg,.png,.pdf" 
                                        onchange="let display = document.getElementById('file-name-display'); if(this.files.length > 0) { display.textContent = 'Terpilih: ' + this.files[0].name; display.classList.remove('hidden'); }">
                                </label>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-12 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-95">
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Sidebar Info --}}
                <div class="space-y-6">
                    {{-- Card Sisa Kuota --}}
                    <div class="group bg-white/80 backdrop-blur-md shadow-xl rounded-[30px] border border-emerald-100 p-6 overflow-hidden relative transition-all duration-300 hover:border-emerald-400">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-50 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                        
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 relative group-hover:text-emerald-700 transition-colors">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Sisa Kuota Pengajuan
                        </h3>
                        
                        <div class="flex items-end gap-2 mb-2 relative">
                            <span class="text-6xl font-black text-emerald-600 tracking-tighter transition-transform group-hover:scale-110 origin-left duration-300">
                                {{ Auth::user()->kuota_cuti ?? 0 }}
                            </span>
                            <span class="text-gray-400 font-bold mb-2">HARI</span>
                        </div>
                        <p class="text-xs text-gray-500 italic font-medium relative">Berlaku hingga 31 Des {{ date('Y') }}</p>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3 relative">
                            <div class="flex justify-between text-sm p-2 hover:bg-emerald-50 rounded-lg transition-colors border border-transparent hover:border-emerald-100">
                                <span class="text-gray-500 font-medium">Cuti / Tukar Shift Diambil</span>
                                <span class="font-bold text-gray-700">
                                    @php
                                        $totalCuti = \App\Models\Pengajuan::where('karyawan_id', Auth::id())
                                            // Pastikan menggunakan nama kolom yang benar sesuai database Anda
                                            // Jika Anda menggunakan relasi kategori, gunakan whereHas
                                            ->where('status', 'Disetujui')
                                            ->get()
                                            ->filter(function($item) {
                                                // Kita filter secara manual berdasarkan kategori jika kolom di DB berbeda
                                                return str_contains(strtolower($item->kategoriPengajuan->nama_pengajuan ?? ''), 'cuti');
                                            })
                                            ->sum(function($item) {
                                                return \Carbon\Carbon::parse($item->tgl_mulai)->diffInDays(\Carbon\Carbon::parse($item->tgl_selesai)) + 1;
                                            });
                                    @endphp
                                    {{ $totalCuti }} Hari
                                </span>
                            </div>
                            
                            <div class="flex justify-between text-sm p-2 hover:bg-emerald-50 rounded-lg transition-colors border border-transparent hover:border-emerald-100">
                                <span class="text-gray-500 font-medium">Izin Sakit Terpakai</span>
                                <span class="font-bold text-gray-700">
                                    @php
                                        $totalSakit = \App\Models\Pengajuan::where('karyawan_id', Auth::id())
                                            ->where('status', 'Disetujui')
                                            ->get()
                                            ->filter(function($item) {
                                                return str_contains(strtolower($item->kategoriPengajuan->nama_pengajuan ?? ''), 'sakit');
                                            })
                                            ->sum(function($item) {
                                                return \Carbon\Carbon::parse($item->tgl_mulai)->diffInDays(\Carbon\Carbon::parse($item->tgl_selesai)) + 1;
                                            });
                                    @endphp
                                    {{ $totalSakit }} Hari
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Informasi Penting --}}
                    <div class="bg-emerald-800 shadow-xl rounded-[30px] p-8 text-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:rotate-12 transition-transform">
                            <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                        </div>
                        <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Informasi Penting
                        </h3>
                        <ul class="text-sm space-y-3 opacity-90 leading-relaxed font-medium">
                            <li class="flex gap-2"><span>•</span> <span>Pengajuan cuti / tukar shift maksimal <strong class="text-emerald-300">H-3</strong> sebelum tanggal mulai.</span></li>
                            <li class="flex gap-2"><span>•</span> <span>Izin sakit wajib melampirkan <strong class="text-emerald-300">Surat Keterangan Dokter</strong> asli.</span></li>
                            <li class="flex gap-2"><span>•</span> <span>Verifikasi dilakukan admin dalam <strong class="text-emerald-300">1x24 jam</strong> jam kerja.</span></li>
                            <li class="flex gap-2"><span>•</span> <span>Pastikan data yang diisi sudah sesuai dengan jadwal shift divisi masing-masing.</span></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>