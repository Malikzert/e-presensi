<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h2 class="text-3xl font-black text-emerald-900 tracking-tight leading-none">{{ $title ?? 'Admin Panel' }}</h2>
                <p class="text-emerald-700 font-bold text-xs mt-1 uppercase tracking-widest opacity-70">RSU ANNA MEDIKA</p>
            </div>
        </div>

        <div class="flex bg-white/60 backdrop-blur-xl p-1.5 rounded-2xl shadow-xl shadow-emerald-900/5 border border-white/50 w-fit">
            <a href="{{ route('admin.dashboards') }}" 
               class="px-5 py-2 rounded-xl font-bold transition-all duration-300 text-sm {{ request()->routeIs('admin.dashboards') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                Dashboards
            </a>
            <a href="{{ route('admin.kehadirans') }}" 
               class="px-5 py-2 rounded-xl font-bold transition-all duration-300 text-sm {{ request()->routeIs('admin.kehadirans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                Kehadirans
            </a>
            <a href="{{ route('admin.pengajuans') }}" 
               class="px-5 py-2 rounded-xl font-bold transition-all duration-300 text-sm {{ request()->routeIs('admin.pengajuans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                Pengajuans
            </a>
            <a href="{{ route('admin.karyawans') }}" 
               class="px-5 py-2 rounded-xl font-bold transition-all duration-300 text-sm {{ request()->routeIs('admin.karyawans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                Karyawans
            </a>
        </div>

        <div class="flex items-center gap-3 bg-white/40 backdrop-blur-md p-1.5 pl-4 rounded-2xl border border-white/50 shadow-sm">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-black text-emerald-900 leading-none">{{ Auth::user()->name }}</p>
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Administrator</p>
            </div>
            
            <div class="flex gap-1">
                <a href="{{ route('profile.edit') }}" class="p-2 text-emerald-700 hover:bg-emerald-100 rounded-xl transition-colors" title="Edit Profile">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Logout Session">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>