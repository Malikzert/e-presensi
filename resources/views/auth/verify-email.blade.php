<x-guest-layout>
    <div class="fixed inset-0 flex flex-col md:flex-row z-50 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/rsanna.jpg') }}');">
        
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white/80 backdrop-blur-md">
            <div class="max-w-md w-full py-10">
                
                <div class="text-center mb-8">
                    <div class="mb-4 inline-block">
                        <img src="{{ asset('images/logors.png') }}" alt="Logo RSU Anna Medika" class="h-20 w-auto mx-auto object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Verifikasi Email</h2>
                    <p class="text-gray-600 text-sm mt-4 leading-relaxed">
                        {{ __('Terima kasih telah mendaftar! Sebelum melangkah lebih jauh, silakan verifikasi email Anda melalui link yang baru saja kami kirimkan ke kotak masuk Anda.') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded-r-lg animate-pulse">
                        {{ __('Link verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                    </div>
                @endif

                <div class="mt-8 flex flex-col gap-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-lg shadow-green-100 uppercase tracking-widest text-xs">
                            {{ __('Kirim Ulang Email Verifikasi') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-600 transition underline decoration-2 underline-offset-4">
                            {{ __('Keluar (Log Out)') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-green-900/40 border-l border-white/20">
            <img src="{{ asset('images/rsanna.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="RSU Anna Medika">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent"></div>
            <div class="relative h-full flex flex-col justify-end p-16 text-white">
                <h1 class="text-4xl font-bold mb-4">Keamanan Data <br> Terjamin.</h1>
                <p class="text-lg text-green-50 opacity-90 leading-relaxed">Langkah verifikasi ini memastikan bahwa hanya staf resmi yang dapat mengakses sistem E-Presensi RSU Anna Medika.</p>
            </div>
        </div>
    </div>
</x-guest-layout>