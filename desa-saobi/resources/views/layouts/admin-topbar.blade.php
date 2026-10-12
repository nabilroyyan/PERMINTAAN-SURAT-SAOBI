<header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
    <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        <!-- Left: Mobile Toggle & Page Info -->
        <div class="flex items-center gap-3">
            <button type="button"
                    @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition focus:outline-none"
                    aria-label="Buka Navigasi">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-500">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <span class="text-slate-300">/</span>
                <span>Pemerintah Desa Saobi</span>
            </div>
        </div>

        <!-- Right: Actions & User Dropdown -->
        <div class="flex items-center gap-3">
            <!-- Role Badge -->
            <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ Auth::user()->isKepalaDesa() ? 'bg-amber-50 text-amber-800 border border-amber-300' : 'bg-purple-50 text-purple-800 border border-purple-200' }}">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                {{ Auth::user()->role_label }}
            </span>

            <!-- Dropdown -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-200 text-xs font-semibold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition shadow-2xs">
                        <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                        <svg class="fill-current h-3.5 w-3.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-500">
                        Admin: <strong>{{ Auth::user()->email }}</strong>
                    </div>

                    <x-dropdown-link :href="route('profile.edit')" class="hover:bg-slate-50 text-slate-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Profil Akun') }}
                        </div>
                    </x-dropdown-link>

                    <x-dropdown-link :href="route('admin.settings.index')" class="hover:bg-slate-50 text-slate-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                            {{ __('Profil Kades & TTD') }}
                        </div>
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600 hover:bg-red-50">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                {{ __('Keluar (Log Out)') }}
                            </div>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>
