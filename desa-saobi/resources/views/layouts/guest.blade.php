<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Layanan Surat Desa Saobi') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo_desa_saobi.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col justify-center items-center p-4 selection:bg-emerald-500 selection:text-white">
        @php($desaFooter = \App\Models\VillageSetting::first())
        <!-- Global Loading Animation -->
        @include('layouts.partials.loading-indicator')

        <div class="w-full max-w-md sm:max-w-lg px-2 sm:px-0 transition-all duration-200">
            <!-- Brand Header -->
            <div class="text-center mb-5 sm:mb-6">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ strtoupper($desaFooter?->nama_desa ?? 'DESA SAOBI') }}
                </h1>
                <img src="{{ asset('images/logo_desa_saobi.png') }}" alt="Logo Desa Saobi" class="w-16 h-auto sm:w-20 object-contain mx-auto mt-3 mb-2">
                <p class="text-xs text-emerald-700 font-semibold tracking-wider uppercase mt-0.5">
                    Sistem Layanan Surat Mandiri Online
                </p>
            </div>

            <!-- Auth Box Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-900/5 p-4 sm:p-7 md:p-8">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-slate-400 mt-6">
                &copy; {{ date('Y') }} Pemerintah {{ $desaFooter?->nama_desa ?? 'Desa Saobi' }}@if (filled($desaFooter?->wilayahLengkap())) &bull; {{ $desaFooter->wilayahLengkap() }}@endif
            </p>
        </div>
    </body>
</html>
