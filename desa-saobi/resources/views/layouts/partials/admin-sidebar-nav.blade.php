<div>
    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">
        Menu Utama
    </p>

    <div class="mt-2 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition group {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </div>
            @if(request()->routeIs('admin.dashboard'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-200"></span>
            @endif
        </a>

        <!-- Permohonan Surat -->
        <a href="{{ route('admin.permohonan.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition group {{ request()->routeIs('admin.permohonan.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.permohonan.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Permohonan Surat</span>
            </div>
            @if($pendingCount > 0)
                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('admin.permohonan.*') ? 'bg-amber-400 text-slate-900' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' }}">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>

        <!-- Data Penduduk -->
        <a href="{{ route('admin.penduduk.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition group {{ request()->routeIs('admin.penduduk.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.penduduk.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Data Penduduk</span>
            </div>
            @if(request()->routeIs('admin.penduduk.*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-200"></span>
            @endif
        </a>

        <!-- Jenis & No. Surat -->
        <a href="{{ route('admin.jenis-surat.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition group {{ request()->routeIs('admin.jenis-surat.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.jenis-surat.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <span>Jenis & Format Surat</span>
            </div>
            @if(request()->routeIs('admin.jenis-surat.*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-200"></span>
            @endif
        </a>

        <!-- Pengaturan Kades & TTD -->
        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition group {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-emerald-400' }} transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Profil Kades & TTD</span>
            </div>
            @if(request()->routeIs('admin.settings.*'))
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-200"></span>
            @endif
        </a>
    </div>
</div>

<div class="pt-4 border-t border-slate-800">
    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">
        Navigasi Cepat
    </p>

    <div class="mt-2 space-y-1">
        <!-- Profil Saya -->
        <a href="{{ route('profile.edit') }}"
           class="flex items-center justify-between px-3.5 py-2 rounded-xl text-sm font-medium transition group {{ request()->routeIs('profile.*') ? 'bg-slate-800 text-white font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' }}">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profil Akun</span>
            </div>
        </a>

        <!-- Halaman Depan -->
        <a href="{{ url('/') }}" target="_blank"
           class="flex items-center justify-between px-3.5 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800/80 transition group">
            <div class="flex items-center gap-3">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                <span>Halaman Utama</span>
            </div>
            <span class="text-[10px] text-slate-400 uppercase">Tab Baru</span>
        </a>
    </div>
</div>
