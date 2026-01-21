<section>
    <header class="border-l-4 border-emerald-500 pl-4">
        <h2 class="text-lg font-bold text-emerald-800 uppercase tracking-wide">
            {{ __('Informasi Profil Pegawai') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui data diri, foto profil, dan alamat email resmi Anda di RSU Anna Medika.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center gap-4 p-4 bg-emerald-50/50 rounded-xl border border-dashed border-emerald-200" 
             x-data="{photoName: null, photoPreview: null}">
            
            <div class="relative">
                <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-100">
                    <template x-if="! photoPreview">
                        <img src="{{ asset('images/users/' . ($user->foto ?? 'default.jpg')) }}" class="h-full w-full object-cover">
                    </template>
                    
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="h-full w-full object-cover">
                    </template>
                </div>

                <label for="foto" class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md border border-emerald-100 cursor-pointer hover:bg-emerald-50 transition">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <input type="file" id="foto" name="foto" class="hidden" accept="image/*"
                           @change="
                                    photoName = $event.target.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($event.target.files[0]);
                           ">
                </label>
            </div>
            
            <div class="text-center">
                <p class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Foto Profil Pegawai</p>
                <p class="text-[10px] text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('foto')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-emerald-700 font-semibold" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="nik" :value="__('Nomor Induk Kependudukan (NIK)')" class="text-emerald-700 font-semibold" />
                <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full bg-gray-100" :value="old('nik', $user->nik)" readonly />
                <p class="text-[10px] text-gray-400 mt-1">*NIK tidak dapat diubah secara mandiri</p>
                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
            </div>

            <div>
                <x-input-label for="nopeg" :value="__('Nomor Pegawai (Nopeg)')" class="text-emerald-700 font-semibold" />
                <x-text-input id="nopeg" name="nopeg" type="text" class="mt-1 block w-full bg-gray-100 italic font-mono" :value="old('nopeg', $user->nopeg)" readonly />
                <p class="text-[10px] text-gray-400 mt-1">*Nopeg bersifat permanen</p>
                <x-input-error class="mt-2" :messages="$errors->get('nopeg')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Jenis Kelamin')" class="text-emerald-700 font-semibold" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Institusi')" class="text-emerald-700 font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            
            <div>
                <x-input-label for="jabatan" :value="__('Jabatan Saat Ini')" class="text-emerald-700 font-semibold" />
                <x-text-input id="jabatan" type="text" class="mt-1 block w-full bg-gray-100" 
                    :value="$user->jabatan->nama_jabatan ?? 'Belum Ditentukan'" readonly />
            </div>

            <div>
                <x-input-label :value="__('Unit Kerja')" class="text-emerald-700 font-semibold" />
                <div class="mt-1 flex flex-wrap gap-2 p-2.5 bg-gray-100 border border-gray-300 rounded-md min-h-[42px]">
                    @forelse($user->units as $unit)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $unit->nama_unit }}
                        </span>
                    @empty
                        <span class="text-xs text-gray-500 italic">Tidak ada unit kerja</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-emerald-100">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 shadow-md transition">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-bold"
                >{{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>