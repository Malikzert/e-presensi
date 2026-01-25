<div class="mb-10 relative overflow-hidden rounded-[40px] p-4 lg:p-6">
    <div class="absolute inset-0 pointer-events-none z-0 shadow-[inset_0_0_80px_rgba(6,78,59,0.07)] rounded-[40px]"></div>
    <div class="absolute inset-0 pointer-events-none z-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.05),transparent_25%),radial-gradient(circle_at_bottom_right,rgba(16,185,129,0.05),transparent_25%)]"></div>

    <div class="relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6">
            
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex-shrink-0 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl lg:text-3xl font-black text-emerald-900 tracking-tight leading-none">{{ $title ?? 'Admin Panel' }}</h2>
                    <p class="text-emerald-700 font-bold text-[10px] lg:text-xs mt-1 uppercase tracking-widest opacity-70">RSU ANNA MEDIKA</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center bg-white/60 backdrop-blur-xl p-1.5 rounded-2xl shadow-xl shadow-emerald-900/5 border border-white/50 w-full lg:w-fit gap-1">
                <a href="{{ route('admin.dashboards') }}" 
                   class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs {{ request()->routeIs('admin.dashboards') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboards</span>
                </a>
                
                <a href="{{ route('admin.kehadirans') }}" 
                   class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs {{ request()->routeIs('admin.kehadirans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                    <span>Kehadirans</span>
                </a>

                <a href="{{ route('admin.pengajuans') }}" 
                   class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs {{ request()->routeIs('admin.pengajuans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Pengajuans</span>
                </a>

                <a href="{{ route('admin.jadwals') }}" 
                    class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs {{ request()->routeIs('admin.jadwals*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Jadwals</span>
                    </a>

                <a href="{{ route('admin.karyawans') }}" 
                   class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold transition-all duration-300 text-xs {{ request()->routeIs('admin.karyawans') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'text-emerald-800/50 hover:text-emerald-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Karyawans</span>
                </a>
            </div>

            <div class="flex items-center justify-between lg:justify-end gap-3 bg-white/40 backdrop-blur-md p-1.5 pl-4 rounded-2xl border border-white/50 shadow-sm">
                <div class="text-right">
                    <p class="text-xs font-black text-emerald-900 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter">Administrator</p>
                </div>
                
                <div class="flex gap-1 border-l border-emerald-100 pl-2">
                    <a href="{{ route('admin.profile.index') }}" 
                    class="p-2 {{ request()->routeIs('admin.profile.index') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-700 hover:bg-emerald-100' }} rounded-xl transition-colors" 
                    title="Edit Profile">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
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

        <div class="h-[2px] w-full bg-gradient-to-r from-emerald-500 via-emerald-200 to-transparent rounded-full opacity-50"></div>
    </div>
</div>