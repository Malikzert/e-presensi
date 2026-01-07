<section class="space-y-6">
    <header class="border-l-4 border-red-500 pl-4">
        <h2 class="text-lg font-bold text-red-800 uppercase tracking-wide">
            {{ __('Penghapusan Akun Pegawai') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 italic">
            {{ __('Setelah akun dihapus, seluruh riwayat presensi dan data medis Anda akan terhapus permanen. Proses ini memerlukan verifikasi ganda dan persetujuan dari bagian SDM/IT.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        class="bg-red-600 hover:bg-red-700 shadow-md"
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        {{ __('Hapus Akun Pegawai') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white" x-data="{ confirmationText: '' }">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-red-700">
                {{ __('Konfirmasi Penghapusan Akun') }}
            </h2>

            <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded-lg">
                <p class="text-sm text-red-600 font-medium">
                    <span class="font-bold">Peringatan:</span> Anda sedang mengajukan penghapusan akun dari sistem RSU Anna Medika. Silakan selesaikan 3 langkah verifikasi di bawah ini:
                </p>
            </div>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('1. Masukkan Password Anda') }}" class="font-bold text-gray-700" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full md:w-3/4 border-gray-300 focus:border-red-500 focus:ring-red-500"
                    placeholder="Password saat ini"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="confirm_text" class="font-bold text-gray-700">
                    {{ __('2. Ketik "KONFIRMASI" untuk melanjutkan') }}
                </x-input-label>
                <x-text-input
                    id="confirm_text"
                    name="confirm_text"
                    type="text"
                    x-model="confirmationText"
                    class="mt-1 block w-full md:w-3/4 border-gray-300 focus:border-red-500 focus:ring-red-500"
                    placeholder="KONFIRMASI"
                    required
                />
                <p class="mt-1 text-[10px] text-gray-500 font-medium italic">*Harus menggunakan huruf kapital</p>
            </div>

            <div class="mt-6 flex items-start gap-3 p-4 bg-amber-50 rounded-lg border border-amber-200">
                <div class="shrink-0 text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-800">3. Status: Menunggu Persetujuan</h4>
                    <p class="text-xs text-amber-700">Setelah menekan tombol hapus, akun akan dinonaktifkan sementara dan menunggu validasi akhir dari Admin SDM RSU Anna Medika sebelum benar-benar dihapus dari database.</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-6">
                    {{ __('Batal') }}
                </x-secondary-button>

                <button 
                    type="submit"
                    :disabled="confirmationText !== 'KONFIRMASI'"
                    :class="confirmationText !== 'KONFIRMASI' ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-red-600 hover:bg-red-700'"
                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150"
                >
                    {{ __('Ajukan Penghapusan') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>