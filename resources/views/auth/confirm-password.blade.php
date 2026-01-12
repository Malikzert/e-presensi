<x-guest-layout>
    <div class="fixed inset-0 flex flex-col md:flex-row z-50 bg-cover bg-center" 
         style="background-image: url('{{ asset('images/rsanna.jpg') }}');">
        
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 md:p-16 bg-white/80 backdrop-blur-md overflow-y-auto">
            <div class="max-w-md w-full py-10" x-data="{ method: 'password' }">
                
                <div class="text-center mb-8">
                    <div class="mb-4 inline-block">
                        <img src="{{ asset('images/logors.png') }}" alt="Logo RSU" class="h-20 w-auto mx-auto object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Konfirmasi Identitas</h2>
                    <p class="text-gray-600 text-sm mt-2">
                        Pilih metode verifikasi untuk melanjutkan ke area sensitif.
                    </p>
                </div>

                <div class="flex bg-gray-100 p-1 rounded-xl mb-6">
                    <button @click="method = 'password'" :class="method === 'password' ? 'bg-white shadow-sm text-green-600' : 'text-gray-500'" class="flex-1 py-2 text-xs font-bold rounded-lg transition">PASSWORD</button>
                    <button @click="method = 'email'" :class="method === 'email' ? 'bg-white shadow-sm text-green-600' : 'text-gray-500'" class="flex-1 py-2 text-xs font-bold rounded-lg transition">EMAIL</button>
                    <button @click="method = 'nama'" :class="method === 'nama' ? 'bg-white shadow-sm text-green-600' : 'text-gray-500'" class="flex-1 py-2 text-xs font-bold rounded-lg transition">NAMA</button>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div x-show="method === 'password'">
                        <x-input-label for="password" value="Kata Sandi Akun" />
                        <x-text-input id="password" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>

                    <div x-show="method === 'email'" x-cloak>
                        <x-input-label for="confirm_email" value="Konfirmasi Email Terdaftar" />
                        <x-text-input id="confirm_email" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl" type="email" placeholder="Masukkan email Anda" />
                        <p class="mt-2 text-[10px] text-gray-500">*Pastikan email sesuai dengan akun yang login saat ini.</p>
                    </div>

                    <div x-show="method === 'nama'" x-cloak>
                        <x-input-label for="confirm_nama" value="Nama Lengkap Karyawan" />
                        <x-text-input id="confirm_nama" class="block mt-1 w-full bg-white/90 border-gray-200 rounded-xl" type="text" placeholder="Masukkan nama lengkap Anda" />
                    </div>

                    <div x-show="method !== 'password'" class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <p class="text-[11px] text-yellow-700 leading-tight">
                            <strong>Catatan Keamanan:</strong> Untuk metode Email/Nama, Anda tetap diwajibkan memasukkan Password di bawah ini sebagai verifikasi akhir sistem.
                        </p>
                        <input type="password" name="password" class="mt-2 block w-full border-gray-200 rounded-lg text-sm focus:ring-green-500" placeholder="Password konfirmasi..." required>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-lg shadow-green-100 uppercase tracking-widest text-xs">
                            Verifikasi Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-green-900/40">
            <img src="{{ asset('images/rsanna.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="RSU Anna Medika">
            <div class="absolute inset-0 bg-gradient-to-t from-green-900 via-transparent to-transparent"></div>
            <div class="relative h-full flex flex-col justify-end p-16 text-white text-right">
                <h1 class="text-4xl font-bold mb-4">Validasi Ganda <br> RSU Anna Medika</h1>
                <p class="text-lg text-green-50 opacity-90 leading-relaxed">Demi melindungi data medis dan privasi karyawan, kami menerapkan prosedur verifikasi sebelum akses diberikan.</p>
            </div>
        </div>
    </div>
</x-guest-layout>