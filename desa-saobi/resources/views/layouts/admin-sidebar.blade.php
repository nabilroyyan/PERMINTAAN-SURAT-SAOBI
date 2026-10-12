@php
    $pendingCount = \App\Models\SuratRequest::where('status', 'diajukan')->count();
@endphp

<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-50 lg:hidden"
     style="display: none;">
</div>

<!-- Mobile Sidebar Slide-over -->
<div x-show="sidebarOpen"
     x-transition:enter="transition ease-in-out duration-300 transform"
     x-transition:enter-start="-translate-x-full"
     x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in-out duration-300 transform"
     x-transition:leave-start="translate-x-0"
     x-transition:leave-end="-translate-x-full"
     class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-300 flex flex-col shadow-2xl lg:hidden border-r border-slate-800"
     style="display: none;">
    <!-- Close button header -->
    <div class="h-16 px-5 flex items-center justify-between border-b border-slate-800/80 bg-slate-950/40">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-extrabold tracking-tight text-white">DESA SAOBI</span>
                <span class="block text-[10px] font-semibold text-emerald-400 tracking-wider uppercase">Panel Admin</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Nav Content -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
        @include('layouts.partials.admin-sidebar-nav', ['pendingCount' => $pendingCount])
    </div>

    <!-- Mobile User Profile & Logout -->
    @include('layouts.partials.admin-sidebar-footer')
</div>

<!-- Desktop Persistent Sidebar -->
<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:w-64 lg:flex lg:flex-col bg-slate-900 text-slate-300 border-r border-slate-800 shadow-xl">
    <!-- Brand Header -->
    <div class="h-16 px-5 flex items-center border-b border-slate-800/80 bg-slate-950/40">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-md shadow-emerald-500/25 group-hover:scale-105 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <span class="block text-base font-extrabold tracking-tight text-white group-hover:text-emerald-400 transition">
                    DESA SAOBI
                </span>
                <span class="block text-[10px] font-semibold text-emerald-400 tracking-wider uppercase">
                    Panel Admin
                </span>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto px-3.5 py-5 space-y-6">
        @include('layouts.partials.admin-sidebar-nav', ['pendingCount' => $pendingCount])
    </div>

    <!-- Desktop User Profile & Logout -->
    @include('layouts.partials.admin-sidebar-footer')
</aside>
