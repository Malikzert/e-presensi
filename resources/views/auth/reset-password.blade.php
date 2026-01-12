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
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Perbarui Kata Sandi</h2>
                    <p class="text-gray-500 text-sm mt-2">
                        {{ __('Silakan masukkan kata sandi baru Anda untuk mengamankan kembali akun Anda.') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <x-input-label for="email" :value="__('Alamat Email')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-100 border-gray-200 rounded-xl shadow-sm text-gray-500 cursor-not-allowed" 
                            type="email" name="email" :value="old('email', $request->email)" required readonly />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Kata Sandi Baru')" />
                        <x-text-input id="password" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl shadow-sm focus:ring-green-500" 
                            type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl shadow-sm focus:ring-green-500"
                            type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-lg shadow-green-100 uppercase tracking-widest text-xs">
                            {{ __('Simpan Kata Sandi Baru') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-green-900/40">
            <img src="{{ asset('images/rsanna.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="RSU Anna Medika">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent"></div>
            <div class="relative h-full flex flex-col justify-end p-16 text-white text-right">
                <h1 class="text-4xl font-bold mb-4">Langkah Terakhir <br> Pemulihan Akun</h1>
                <p class="text-lg text-green-50 opacity-90 leading-relaxed">Gunakan kombinasi karakter, angka, dan simbol untuk keamanan yang lebih kuat bagi akun sistem Anda.</p>
            </div>
        </div>
    </div>
</x-guest-layout>