<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-15">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-100/40 via-transparent to-white/60"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="relative z-10 py-12 min-h-screen" x-data="attendanceApp()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 text-center">
                <span class="px-4 py-1.5 bg-emerald-600 text-white text-[10px] font-black rounded-full uppercase tracking-[0.2em] shadow-lg shadow-emerald-200">
                    Sistem Presensi Digital
                </span>
                <h2 class="text-4xl font-black text-emerald-900 mt-4 tracking-tight">Presensi RSU Anna Medika</h2>
                <p class="text-gray-500 mt-2 font-medium">Silahkan lakukan pencatatan kehadiran sesuai dengan shift tugas Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="md:col-span-2 bg-white/80 backdrop-blur-xl overflow-hidden shadow-2xl rounded-[40px] border border-white p-10">
                    <div class="flex flex-col items-center justify-center space-y-8">
                        <div class="text-center">
                            <div class="text-6xl font-black text-emerald-700 tracking-tighter drop-shadow-sm" x-text="currentTime"></div>
                            <div class="text-sm text-emerald-600/60 font-bold uppercase tracking-[0.3em] mt-3" x-text="currentDate"></div>
                        </div>

                        <div class="w-full h-px bg-gradient-to-r from-transparent via-emerald-100 to-transparent"></div>

                        <div class="flex flex-col sm:flex-row gap-6 w-full">
                            <button 
                                @click="checkIn()" 
                                :disabled="status === 'checked_in'"
                                :class="status === 'checked_in' ? 'opacity-40 cursor-not-allowed grayscale' : 'bg-emerald-600 hover:bg-emerald-700 hover:scale-[1.02] shadow-xl shadow-emerald-200'"
                                class="flex-1 py-5 rounded-[25px] text-white font-black text-xl transition-all flex flex-col items-center justify-center gap-2 group">
                                <svg class="w-8 h-8 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h12m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                CHECK IN
                            </button>

                            <button 
                                @click="checkOut()" 
                                :disabled="status === 'idle' || status === 'checked_out'"
                                :class="(status === 'idle' || status === 'checked_out') ? 'opacity-40 cursor-not-allowed grayscale' : 'bg-rose-500 hover:bg-rose-600 hover:scale-[1.02] shadow-xl shadow-rose-200'"
                                class="flex-1 py-5 rounded-[25px] text-white font-black text-xl transition-all flex flex-col items-center justify-center gap-2 group">
                                <svg class="w-8 h-8 group-hover:-rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                CHECK OUT
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-emerald-900 text-white shadow-2xl rounded-[40px] p-8 relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-10">
                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path></svg>
                        </div>
                        
                        <h3 class="font-black text-lg mb-6 uppercase tracking-widest border-b border-emerald-800 pb-4">Info Sesi</h3>
                        
                        <div class="space-y-6 relative z-10">
                            <div>
                                <p class="text-[10px] text-emerald-400 font-black uppercase tracking-widest">Waktu Masuk</p>
                                <p class="text-2xl font-black text-white" x-text="checkInTimeDisplay || '--:--'"></p>
                            </div>
                            
                            <div>
                                <p class="text-[10px] text-emerald-400 font-black uppercase tracking-widest italic">Durasi Kerja</p>
                                <p class="text-3xl font-black text-emerald-300 tabular-nums" x-text="workDurationDisplay"></p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-emerald-800">
                            <template x-if="status === 'checked_in'">
                                <span class="flex items-center gap-2 text-xs font-bold text-emerald-400 animate-pulse">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full shadow-[0_0_8px_#34d399]"></span>
                                    SEDANG BERTUGAS
                                </span>
                            </template>
                            <template x-if="status === 'idle'">
                                <span class="flex items-center gap-2 text-xs font-bold text-gray-400">
                                    <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                                    BELUM ABSEN
                                </span>
                            </template>
                            <template x-if="status === 'checked_out'">
                                <span class="flex items-center gap-2 text-xs font-bold text-rose-400">
                                    <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                    SUDAH SELESAI
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white/60 backdrop-blur-md p-6 rounded-[30px] border border-white">
                        <p class="text-[10px] font-black text-emerald-800 uppercase mb-2">💡 Pengingat</p>
                        <p class="text-xs text-gray-600 leading-relaxed italic">"Jangan lupa memperbarui log laporan pasien sebelum melakukan check-out."</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    function attendanceApp() {
        return {
            currentTime: '',
            currentDate: '',
            status: 'idle', 
            checkInTime: null,
            checkInTimeDisplay: '',
            workDurationDisplay: '0j 0m 0s',

            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);

                const savedCheckIn = localStorage.getItem('rsu_checkin_time');
                const savedStatus = localStorage.getItem('rsu_attendance_status');

                if (savedCheckIn && savedStatus === 'checked_in') {
                    this.checkInTime = new Date(savedCheckIn);
                    this.checkInTimeDisplay = this.checkInTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    this.status = 'checked_in';
                }
            },

            updateTime() {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
                this.currentDate = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                
                if (this.status === 'checked_in') {
                    this.updateDuration();
                }
            },

            checkIn() {
                this.checkInTime = new Date();
                this.checkInTimeDisplay = this.checkInTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                this.status = 'checked_in';
                
                localStorage.setItem('rsu_checkin_time', this.checkInTime);
                localStorage.setItem('rsu_attendance_status', 'checked_in');

                Swal.fire({
                    title: 'Check-In Berhasil!',
                    text: 'Selamat bertugas! Tetap jaga kesehatan dan semangat melayani.',
                    icon: 'success',
                    confirmButtonColor: '#059669',
                    customClass: { popup: 'rounded-[30px]' }
                });
            },

            checkOut() {
                Swal.fire({
                    title: 'Selesai Bertugas?',
                    text: "Pastikan semua tugas hari ini telah diselesaikan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Selesai',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-[30px]' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.status = 'checked_out';
                        this.workDurationDisplay = '0j 0m 0s';
                        this.checkInTimeDisplay = '';
                        localStorage.removeItem('rsu_checkin_time');
                        localStorage.setItem('rsu_attendance_status', 'checked_out');
                        
                        Swal.fire({
                            title: 'Terima Kasih!',
                            text: 'Sampai jumpa di shift berikutnya.',
                            icon: 'success',
                            confirmButtonColor: '#059669',
                            customClass: { popup: 'rounded-[30px]' }
                        });
                    }
                });
            },

            updateDuration() {
                if (!this.checkInTime) return;
                const diff = new Date() - this.checkInTime;
                const hours = Math.floor(diff / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                this.workDurationDisplay = `${hours}j ${minutes}m ${seconds}s`;
            }
        }
    }
    </script>
</x-app-layout>