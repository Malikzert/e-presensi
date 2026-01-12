<x-guest-layout>
    <div class="fixed inset-0 flex flex-col md:flex-row z-50 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/rsanna.jpg') }}');">
        
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 overflow-y-auto bg-white/80 backdrop-blur-md">
            <div class="max-w-md w-full py-10">
                
                <div class="text-center mb-8">
                    <div class="mb-4 inline-block">
                        <img src="{{ asset('images/logors.png') }}" 
                            alt="Logo RSU Anna Medika" 
                            class="h-20 w-auto mx-auto object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">RSU Anna Medika</h2>
                    <p class="text-gray-500 text-sm italic">Silakan masuk ke akun Anda</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl shadow-sm focus:ring-green-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between items-center">
                            <x-input-label for="password" :value="__('Password')" />
                            @if (Route::has('password.request'))
                                <a class="text-xs text-green-600 hover:underline font-semibold" href="{{ route('password.request') }}">
                                    Lupa Kata Sandi?
                                </a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl shadow-sm focus:ring-green-500" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="block mt-4" x-data="{ checked: false }">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" x-model="checked" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Biarkan saya tetap masuk') }}</span>
                        </label>
                        <div x-show="checked" x-transition class="mt-1 text-[10px] text-green-600 font-medium italic ms-6">
                            Tetap login selama 1 Bulan
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-lg shadow-green-100 uppercase tracking-widest text-xs">
                            {{ __('Masuk Sekarang') }}
                        </button>
                    </div>

                    <div class="flex items-center my-6">
                        <div class="flex-1 h-px bg-gray-300"></div>
                        <div class="px-3 text-gray-400 text-[10px] uppercase tracking-widest">Atau Login Dengan</div>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ url('auth/google') }}" class="w-full flex items-center justify-center gap-2 bg-white border border-gray-200 py-2.5 rounded-xl hover:bg-gray-50 transition shadow-sm font-semibold text-sm text-gray-700">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5" alt="Google">
                            Google
                        </a>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            {{ __('Belum punya akun?') }} 
                            <a class="font-bold text-green-600 hover:underline" href="{{ route('register') }}">
                                {{ __('Daftar Sekarang') }}
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-green-900/40">
            <img src="{{ asset('images/rsanna.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="RSU Anna Medika">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent"></div>
            <div class="relative h-full flex flex-col justify-end p-16 text-white">
                <h1 class="text-4xl font-bold mb-4 drop-shadow-lg leading-tight text-white">Layanan Prima <br> Untuk Kesembuhan Anda</h1>
                <p class="text-lg text-green-50 opacity-90 leading-relaxed drop-shadow-md">Masuk untuk memantau kehadiran, jadwal tugas, dan layanan kesehatan digital RSU Anna Medika secara real-time.</p>
            </div>
        </div>
    </div>
</x-guest-layout>