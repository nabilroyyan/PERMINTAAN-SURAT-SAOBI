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
    <body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col justify-center items-center p-4 selection:bg-emerald-500 selection:text-white">
        <!-- Global Loading Animation -->
        @include('layouts.partials.loading-indicator')

        <div class="w-full max-w-md sm:max-w-lg px-2 sm:px-0 transition-all duration-200">
            <!-- Brand Header -->
            <div class="text-center mb-5 sm:mb-6">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white mx-auto shadow-lg shadow-emerald-600/30">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-3 sm:mt-4">
                    DESA SAOBI
                </h1>
                <p class="text-xs text-emerald-700 font-semibold tracking-wider uppercase mt-0.5">
                    Sistem Layanan Surat Mandiri Online
                </p>
            </div>

            <!-- Auth Box Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-900/5 p-4 sm:p-7 md:p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                &copy; {{ date('Y') }} Pemerintah Desa Saobi &bull; Kec. Kangayan, Kab. Sumenep
            </p>
        </div>
    </body>
</html>
