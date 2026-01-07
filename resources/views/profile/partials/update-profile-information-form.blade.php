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
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/'.$user->profile_photo) }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-emerald-600 text-white text-4xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </template>
                    
                    <template x-if="photoPreview">
                        <img :src="photoPreview" class="h-full w-full object-cover">
                    </template>
                </div>

                <label for="profile_photo" class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md border border-emerald-100 cursor-pointer hover:bg-emerald-50 transition">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*"
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
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Nama Lengkap')" class="text-emerald-700 font-semibold" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="nip" :value="__('NIP / Nomor Induk Pegawai')" class="text-emerald-700 font-semibold" />
                <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full bg-gray-50 border-gray-200" :value="old('nip', $user->nip)" placeholder="Contoh: AM-2024001" />
                <x-input-error class="mt-2" :messages="$errors->get('nip')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Institusi')" class="text-emerald-700 font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full focus:border-emerald-500 focus:ring-emerald-500" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

            <div>
                <x-input-label for="phone" :value="__('Nomor Telepon / WA')" class="text-emerald-700 font-semibold" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">+62</span>
                    </div>
                    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full pl-12 focus:border-emerald-500 focus:ring-emerald-500" :value="old('phone', $user->phone)" placeholder="81234567XXX" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="department" :value="__('Unit Kerja / Departemen')" class="text-emerald-700 font-semibold" />
                <select id="department" name="department" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                    <option value="">Pilih Departemen</option>
                    <option value="Medis" {{ old('department', $user->department) == 'Medis' ? 'selected' : '' }}>Tenaga Medis (Dokter/Perawat)</option>
                    <option value="Administrasi" {{ old('department', $user->department) == 'Administrasi' ? 'selected' : '' }}>Administrasi & Umum</option>
                    <option value="Farmasi" {{ old('department', $user->department) == 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                    <option value="Laboratorium" {{ old('department', $user->department) == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('department')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-emerald-100">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 shadow-md">
                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                {{ __('Simpan Perubahan') }}
            </button>
            </div>
    </form>
</section>