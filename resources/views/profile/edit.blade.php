<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-emerald-800 leading-tight">
            {{ __('Manajemen Profil Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen relative overflow-hidden bg-gray-100">
        
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/rsanna.jpg') }}" alt="RSU Anna Medika" class="w-full h-full object-cover opacity-20">
        </div>

        <div class="absolute inset-0 z-0 bg-gradient-to-tr from-emerald-100/80 via-transparent to-white/50"></div>

        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="p-4 sm:p-8 bg-white/90 backdrop-blur-sm shadow-xl border border-emerald-100 sm:rounded-2xl transition hover:shadow-emerald-200/50">
                        <div class="max-w-full">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="p-4 sm:p-8 bg-white/90 backdrop-blur-sm shadow-xl border border-emerald-100 sm:rounded-2xl transition hover:shadow-emerald-200/50">
                        <div class="max-w-full">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-red-50/90 backdrop-blur-sm shadow-xl border border-red-200 sm:rounded-2xl transition hover:shadow-red-200/50">
                        <div class="max-w-full">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>