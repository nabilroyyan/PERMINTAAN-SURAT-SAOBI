<nav x-data="{ open: false }" class="bg-white border-b border-emerald-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center">
                    @php
                        $homeRoute = Auth::user()->canAccessAdminPanel() ? route('admin.dashboard') : route('client.dashboard');
                    @endphp
                    <a href="{{ $homeRoute }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-base font-extrabold tracking-tight text-gray-900 group-hover:text-emerald-700 transition">
                                DESA SAOBI
                            </span>
                            <span class="block text-xs font-medium text-emerald-600 tracking-wider uppercase">
                                Layanan Surat Online
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    @if (Auth::user()->canAccessAdminPanel())
                        <!-- Admin Nav -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.permohonan.index') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('admin.permohonan.*') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Permohonan Surat
                        </a>

                        <a href="{{ route('admin.penduduk.index') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('admin.penduduk.*') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Penduduk
                        </a>

                        <a href="{{ route('admin.jenis-surat.index') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('admin.jenis-surat.*') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Jenis & No. Surat
                        </a>

                        <a href="{{ route('admin.settings.index') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('admin.settings.*') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Kepala Desa & TTD
                        </a>
                    @else
                        <!-- Client Nav -->
                        <a href="{{ route('client.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('client.dashboard') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            Dashboard
                        </a>

                        <a href="{{ route('client.surat.create') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('client.surat.create') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat Permohonan
                        </a>

                        <a href="{{ route('client.surat.index') }}"
                           class="inline-flex items-center px-4 py-2 border-b-2 text-sm font-semibold transition {{ request()->routeIs('client.surat.index') || request()->routeIs('client.surat.show') ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-600 hover:text-emerald-600 hover:border-emerald-300' }}">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Riwayat Surat
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Role Tag -->
                <span class="me-3 px-2.5 py-1 text-xs font-semibold rounded-full {{ Auth::user()->role_badge_class }}">
                    {{ Auth::user()->role_label }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-emerald-200 text-sm leading-4 font-semibold rounded-lg text-gray-700 bg-white hover:bg-emerald-50 hover:text-emerald-800 focus:outline-none transition">
                            <div class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs me-2 font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 text-xs text-gray-500">
                            Masuk sebagai <strong>{{ Auth::user()->email }}</strong>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-emerald-50 hover:text-emerald-700">
                            {{ __('Profil Akun') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                {{ __('Keluar (Log Out)') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-emerald-700 hover:bg-emerald-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-emerald-50/50 border-t border-emerald-100">
        <div class="pt-2 pb-3 space-y-1">
            @if (Auth::user()->canAccessAdminPanel())
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Dashboard
                </a>
                <a href="{{ route('admin.permohonan.index') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Permohonan Surat
                </a>
                <a href="{{ route('admin.penduduk.index') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Data Penduduk
                </a>
                <a href="{{ route('admin.jenis-surat.index') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Jenis & No. Surat
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Kepala Desa & TTD
                </a>
            @else
                <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Dashboard
                </a>
                <a href="{{ route('client.surat.create') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Buat Permohonan
                </a>
                <a href="{{ route('client.surat.index') }}" class="block px-4 py-2 text-base font-medium text-emerald-800 hover:bg-emerald-100">
                    Riwayat Surat
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-emerald-200 bg-white">
            <div class="px-4">
                <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }} ({{ Auth::user()->role_label }})</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">
                    Profil Akun
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
