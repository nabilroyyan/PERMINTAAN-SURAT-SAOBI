<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Layanan Surat Desa Saobi') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white border-b border-slate-200/80 shadow-sm">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @elseif (View::hasSection('header'))
            <header class="bg-white border-b border-slate-200/80 shadow-sm">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Global Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
            @if (session('success'))
                <div class="p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 py-6">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} <strong>Pemerintah Desa Saobi</strong>, Kecamatan Kangayan, Kabupaten Sumenep.</p>
                <p class="text-emerald-700 font-medium">Sistem Permintaan & Layanan Surat Mandiri Online</p>
            </div>
        </footer>
    </body>
</html>
