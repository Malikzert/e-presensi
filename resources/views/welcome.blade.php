<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Selamat Datang - RSU Anna Medika</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @php
            // Logika Penentuan Warna: Biru untuk Admin, Hijau untuk User/Guest
            $isAdmin = auth()->check() && auth()->user()->is_admin;
            $themeColor = $isAdmin ? 'blue' : 'green';
            $bgColor = $isAdmin ? '#f0f9ff' : '#f0fdf4'; // Light Blue vs Light Green
            $patternColor = $isAdmin ? '%233b82f6' : '%2322c55e'; // Blue-500 vs Green-500
        @endphp

        <style>
            [x-cloak] { display: none !important; }
            .bg-medical-pattern {
                background-color: {{ $bgColor }};
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='{{ $patternColor }}' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="bg-medical-pattern text-slate-800 antialiased min-h-screen flex flex-col relative">
        
        <div class="fixed inset-0 z-0">
            <img src="{{ asset('images/rsanna.jpg') }}" alt="Background RS" class="w-full h-full object-cover opacity-15 grayscale-[20%]">
            <div class="absolute inset-0 bg-gradient-to-b from-{{ $themeColor }}-50/50 via-white/20 to-{{ $themeColor }}-100/60"></div>
        </div>

        <header class="relative z-10 w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-3 bg-white/50 backdrop-blur-sm p-2 rounded-2xl border border-white/50 shadow-sm">
                <img src="{{ asset('images/logors.png') }}" alt="Logo RS" class="h-12 w-auto">
                <div class="hidden sm:block">
                    <p class="font-bold text-{{ $themeColor }}-700 leading-tight uppercase">RSU ANNA MEDIKA</p>
                    <p class="text-[10px] text-{{ $themeColor }}-600 tracking-widest uppercase font-semibold">Sistem Informasi Karyawan</p>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex gap-4">
                    @auth
                        @php
                            $dashboardUrl = auth()->user()->is_admin ? route('admin.dashboards') : url('/dashboard');
                        @endphp
                        <a href="{{ $dashboardUrl }}" class="px-6 py-2 bg-{{ $themeColor }}-600 text-white rounded-full font-semibold shadow-lg shadow-{{ $themeColor }}-200 hover:bg-{{ $themeColor }}-700 transition-all hover:scale-105 active:scale-95">
                            Masuk Dashboard @if($isAdmin) Admin @endif
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-{{ $themeColor }}-700 font-semibold hover:text-{{ $themeColor }}-800 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-{{ $themeColor }}-600 text-white rounded-full font-semibold shadow-lg shadow-{{ $themeColor }}-200 hover:bg-{{ $themeColor }}-700 transition-all hover:scale-105 active:scale-95">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="relative z-10 flex-grow flex items-center justify-center p-6">
            <div class="max-w-4xl w-full bg-white/90 backdrop-blur-xl rounded-[40px] shadow-2xl overflow-hidden border border-white flex flex-col md:flex-row shadow-{{ $themeColor }}-900/10">
                
                <div class="md:w-1/2 bg-{{ $themeColor }}-600 p-12 text-white flex flex-col justify-center relative overflow-hidden transition-colors duration-500">
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    
                    <div class="mb-8 w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center relative z-10 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2 2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-4 leading-tight relative z-10">
                        {{ $isAdmin ? 'Panel Kontrol Administrasi' : 'Pelayanan Profesional Sepenuh Hati' }}
                    </h1>
                    <p class="text-{{ $themeColor }}-50 text-lg relative z-10">
                        {{ $isAdmin ? 'Selamat datang kembali Admin. Pantau data presensi dan aktivitas karyawan secara real-time.' : 'Silahkan login untuk mengakses sistem presensi dan manajemen karyawan RSU Anna Medika.' }}
                    </p>
                </div>

                <div class="md:w-1/2 p-12 flex flex-col justify-center bg-white/50">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Selamat Datang @auth, {{ explode(' ', auth()->user()->name)[0] }} @endauth</h2>
                    <p class="text-slate-500 mb-8 leading-relaxed">Akses portal resmi kepegawaian untuk monitoring kehadiran dan pengajuan izin kerja.</p>
                    
                    <div class="space-y-4">
                        @auth
                            <a href="{{ $dashboardUrl }}" class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-{{ $themeColor }}-500 hover:bg-{{ $themeColor }}-50 transition-all group shadow-sm hover:shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-{{ $themeColor }}-100 shadow-sm rounded-xl flex items-center justify-center text-{{ $themeColor }}-600 group-hover:bg-{{ $themeColor }}-600 group-hover:text-white transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h12m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">Kembali ke Dashboard</span>
                                        <span class="text-[10px] text-slate-400">Sesi Anda sedang aktif</span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-{{ $themeColor }}-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-{{ $themeColor }}-500 hover:bg-{{ $themeColor }}-50 transition-all group shadow-sm hover:shadow-md">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-{{ $themeColor }}-100 shadow-sm rounded-xl flex items-center justify-center text-{{ $themeColor }}-600 group-hover:bg-{{ $themeColor }}-600 group-hover:text-white transition-all">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h12m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">Masuk ke Sistem</span>
                                        <span class="text-[10px] text-slate-400">Gunakan akun terdaftar</span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-{{ $themeColor }}-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <div class="relative py-4">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-4 text-slate-400 font-bold leading-none tracking-widest">ATAU</span></div>
                            </div>

                            <a href="{{ route('register') }}" class="text-center block w-full py-4 text-slate-600 font-medium hover:text-{{ $themeColor }}-600 transition-colors bg-slate-50 rounded-2xl border border-dashed border-slate-200 hover:border-{{ $themeColor }}-300">
                                Belum memiliki akun? <span class="text-{{ $themeColor }}-600 font-bold underline">Daftar Sekarang</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 p-6 text-center text-slate-500 text-sm font-medium">
            <div class="inline-block px-4 py-1 bg-white/50 backdrop-blur-sm rounded-full border border-white">
                &copy; {{ date('Y') }} RSU Anna Medika. <span class="text-{{ $themeColor }}-600 font-bold">IT Department Team</span>.
            </div>
        </footer>
    </body>
</html>