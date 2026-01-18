<x-app-layout>
    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/rsanna.jpg') }}" class="w-full h-full object-cover opacity-10">
        <div class="absolute inset-0 bg-gradient-to-tr from-emerald-50 via-transparent to-white/80"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen" x-data="{ photoPreview: null }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @include('admin.navs', ['title' => 'Pengaturan Akun'])

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-600 text-white rounded-[25px] shadow-xl shadow-emerald-100 flex justify-between items-center animate-pulse">
                    <span class="font-bold ml-4">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500 text-white rounded-[25px] shadow-xl shadow-rose-100">
                    <ul class="list-disc ml-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/70 backdrop-blur-2xl rounded-[45px] border border-white shadow-2xl overflow-hidden">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 lg:p-12">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col lg:flex-row gap-12">
                        <div class="flex flex-col items-center space-y-4">
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-tr from-emerald-400 to-emerald-600 rounded-[55px] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                                
                                <img :src="photoPreview ? photoPreview : '{{ auth()->user()->foto ? asset('images/users/'.auth()->user()->foto) : asset('images/users/default.jpg') }}'" 
                                     class="relative w-48 h-48 rounded-[50px] object-cover border-4 border-white shadow-2xl"
                                     onerror="this.src='{{ asset('images/users/default.jpg') }}'">
                                
                                <label class="absolute bottom-2 right-2 bg-emerald-600 text-white p-3 rounded-2xl shadow-xl cursor-pointer hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    <input type="file" name="foto" class="hidden" 
                                           @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                                </label>
                            </div>
                            <h3 class="font-black text-emerald-900 uppercase text-xs tracking-widest">Administrator</h3>
                        </div>

                        <div class="flex-1 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-4">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full rounded-[25px] border-emerald-100 bg-white/50 p-4 text-sm focus:ring-emerald-500 shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-4">Alamat Email</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full rounded-[25px] border-emerald-100 bg-white/50 p-4 text-sm focus:ring-emerald-500 shadow-sm" required>
                                </div>
                            </div>

                            <div class="h-[1px] bg-emerald-100 w-full opacity-50"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-4">Password Baru (Opsional)</label>
                                    <input type="password" name="password" placeholder="••••••••" class="w-full rounded-[25px] border-emerald-100 bg-white/50 p-4 text-sm focus:ring-emerald-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-emerald-700 mb-2 ml-4">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full rounded-[25px] border-emerald-100 bg-white/50 p-4 text-sm focus:ring-emerald-500 shadow-sm">
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="w-full md:w-auto px-10 py-4 bg-emerald-600 text-white rounded-[25px] font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-1 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>