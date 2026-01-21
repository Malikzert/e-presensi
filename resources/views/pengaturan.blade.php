<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-emerald-800 tracking-tight">Pengaturan Sistem</h2>
                <p class="text-gray-600 font-medium">Kelola preferensi akun dan privasi Anda di RSU Anna Medika.</p>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" id="settingsForm" class="space-y-6">
                @csrf
                
                {{-- Bagian Pusat Notifikasi (Tanpa Email) --}}
                <div class="group bg-white/80 backdrop-blur-md shadow-xl rounded-[30px] border border-green-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-200/50 hover:border-emerald-400">
                    <div class="p-6 border-b border-gray-50 bg-green-50/50 group-hover:bg-emerald-50 transition-colors">
                        <h3 class="font-bold text-green-800 flex items-center gap-2 group-hover:text-emerald-700">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            Pusat Notifikasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Pengingat Check-in/Out --}}
                        <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-emerald-50/50 transition-colors">
                            <div>
                                <p class="font-bold text-gray-700">Pengingat Check-in/Out</p>
                                <p class="text-xs text-gray-400 font-medium">Notifikasi HP jika belum absen 15 menit sebelum shift.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notif_pengingat" class="sr-only peer" onchange="this.form.submit()" {{ auth()->user()->notif_pengingat ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>

                        {{-- Status Pengajuan --}}
                        <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-emerald-50/50 transition-colors">
                            <div>
                                <p class="font-bold text-gray-700">Status Pengajuan</p>
                                <p class="text-xs text-gray-400 font-medium">Notifikasi saat pengajuan izin/cuti disetujui HRD.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notif_status_pengajuan" class="sr-only peer" onchange="this.form.submit()" {{ auth()->user()->notif_status_pengajuan ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Bagian Privasi & Lokasi --}}
                <div class="group bg-white/80 backdrop-blur-md shadow-xl rounded-[30px] border border-green-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-200/50 hover:border-emerald-400">
                    <div class="p-6 border-b border-gray-50 bg-green-50/50 group-hover:bg-emerald-50 transition-colors">
                        <h3 class="font-bold text-green-800 flex items-center gap-2 group-hover:text-emerald-700">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Privasi & Lokasi
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between p-2 rounded-2xl hover:bg-emerald-50/50 transition-colors">
                            <div>
                                <p class="font-bold text-gray-700">Pelacakan Lokasi Perangkat</p>
                                <p class="text-xs text-gray-400 font-medium">Mengambil koordinat GPS saat melakukan check-in.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="track_lokasi" id="track_lokasi" class="sr-only peer" onchange="handleLocationToggle(this)" {{ auth()->user()->track_lokasi ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Footer Branding --}}
            <div class="text-center py-8">
                <img src="{{ asset('images/logors.png') }}" alt="Logo" class="h-10 w-auto mx-auto grayscale opacity-30 mb-4 transition-all duration-500 hover:grayscale-0 hover:opacity-100 hover:scale-110">
                <p class="text-sm font-bold text-gray-400 tracking-widest uppercase">E-Attendance System v1.0.2</p>
                <p class="text-xs text-gray-400 mt-1 font-medium">© 2026 IT Departement - RSU Anna Medika</p>
                <div class="flex justify-center gap-6 mt-6">
                    <a href="#" class="text-xs text-emerald-600 font-bold hover:text-emerald-800 transition-colors border-b border-transparent hover:border-emerald-800">Ketentuan Layanan</a>
                    <span class="text-gray-300">|</span>
                    <a href="#" class="text-xs text-emerald-600 font-bold hover:text-emerald-800 transition-colors border-b border-transparent hover:border-emerald-800">Kebijakan Privasi</a>
                </div>
            </div>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function handleLocationToggle(checkbox) {
    if (checkbox.checked) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                checkbox.form.submit();
            },
            function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Lokasi Ditolak',
                    text: 'Harap izinkan akses lokasi di browser untuk mengaktifkan fitur ini.',
                    confirmButtonColor: '#10b981'
                });
                checkbox.checked = false;
            }
        );
    } else {
        checkbox.form.submit();
    }
}

@if(session('success'))
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif
</script>
</x-app-layout>