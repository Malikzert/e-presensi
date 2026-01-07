<x-guest-layout>
    <div class="fixed inset-0 flex flex-col md:flex-row z-50 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/rsanna.jpg') }}');">
        
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 overflow-y-auto bg-white/80 backdrop-blur-md">
            <div class="max-w-md w-full py-10" x-data="{ konfirmasi: '' }">
                
                <div class="text-center mb-8">
                    <div class="mb-4 inline-block">
                        <img src="{{ asset('images/logors.png') }}" 
                            alt="Logo RSU Anna Medika" 
                            class="h-20 w-auto mx-auto object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Lupa Kata Sandi</h2>
                    <p class="text-gray-500 text-sm mt-2">
                        {{ __('Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi.') }}
                    </p>
                </div>

                <x-auth-session-status class="mb-4 text-green-600 font-medium" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('Email Terkait')" />
                        <x-text-input id="email" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl shadow-sm focus:ring-green-500" type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan email terdaftar" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-100">
                        <label class="block text-xs font-bold text-green-700 uppercase tracking-wider mb-2">
                            Ketik "KONFIRMASI" untuk melanjutkan:
                        </label>
                        <input type="text" 
                               x-model="konfirmasi" 
                               class="block w-full border-gray-200 rounded-lg text-sm focus:ring-green-500 focus:border-green-500 placeholder-gray-300"
                               placeholder="Ketik di sini..."
                               required>
                        <p class="mt-2 text-[10px] text-gray-500 italic">
                            *Fitur ini memastikan permintaan dilakukan secara sadar.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col gap-3">
                        <button type="submit" 
                            :disabled="konfirmasi.toLowerCase() !== 'konfirmasi'"
                            :class="konfirmasi.toLowerCase() === 'konfirmasi' ? 'bg-green-600 hover:bg-green-700 shadow-green-100' : 'bg-gray-400 cursor-not-allowed'"
                            class="w-full text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-lg uppercase tracking-widest text-xs">
                            {{ __('Kirim Link Reset Password') }}
                        </button>

                        <a href="{{ route('login') }}" class="text-center text-sm font-bold text-gray-500 hover:text-green-600 transition">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-green-900/40">
            <img src="{{ asset('images/rsanna.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="RSU Anna Medika">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent"></div>
            <div class="relative h-full flex flex-col justify-end p-16 text-white text-right">
                <h1 class="text-4xl font-bold mb-4">Keamanan Akun <br> Anda Prioritas Kami</h1>
                <p class="text-lg text-green-50 opacity-90 leading-relaxed">Pastikan email yang Anda masukkan aktif untuk menerima prosedur pemulihan akun RSU Anna Medika.</p>
            </div>
        </div>
    </div>
</x-guest-layout>