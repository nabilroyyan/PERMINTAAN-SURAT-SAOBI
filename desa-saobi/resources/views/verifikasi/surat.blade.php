<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Keaslian Surat - {{ $desa->nama_desa ?? 'Desa Saobi' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800 min-h-screen">
    @php
        $namaPemohon = $surat->data_pemohon['nama'] ?? $surat->user->name ?? '-';
        $nik = $surat->data_pemohon['nik'] ?? $surat->user->nik ?? '-';
        $nikMasked = strlen($nik) > 8 ? substr($nik, 0, 4) . str_repeat('*', strlen($nik) - 8) . substr($nik, -4) : $nik;
        $isValid = $surat->status === 'completed';
    @endphp

    <div class="min-h-screen flex flex-col">
        <header class="bg-emerald-700 text-white shadow-md">
            <div class="max-w-3xl mx-auto px-4 py-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold tracking-tight">
                        {{ $desa->nama_desa ?? 'Pemerintah Desa Saobi' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-100">
                        @if (filled($desa?->wilayahLengkap())){{ $desa->wilayahLengkap() }} &bull; @endif Verifikasi Keaslian Surat
                    </p>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-3xl mx-auto px-4 py-8">
            @if ($isValid)
                <div class="rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white shadow-lg flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold">Surat Terverifikasi Asli</h2>
                        <p class="text-sm text-emerald-50 mt-1">
                            Dokumen ini terdaftar dan diterbitkan secara resmi oleh Pemerintah
                            {{ $desa->nama_desa ?? 'Desa Saobi' }}.
                        </p>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-amber-50 border border-amber-200 p-6 text-amber-900 shadow-sm flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold">Surat Belum Diterbitkan</h2>
                        <p class="text-sm mt-1">
                            Data surat ditemukan, namun statusnya belum <strong>selesai/terbit</strong>.
                            Dokumen ini belum dapat dinyatakan sah.
                        </p>
                    </div>
                </div>
            @endif

            <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Data Surat</h3>
                </div>
                <dl class="divide-y divide-slate-100">
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">Jenis Surat</dt>
                        <dd class="font-bold text-slate-800 text-right">{{ $surat->suratType->nama ?? 'Surat Keterangan' }}</dd>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">Nomor Surat</dt>
                        <dd class="font-mono font-bold text-slate-800 text-right">{{ $surat->nomor_surat ?? '-' }}</dd>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">Nama Pemohon</dt>
                        <dd class="font-semibold text-slate-800 text-right">{{ $namaPemohon }}</dd>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">NIK</dt>
                        <dd class="font-mono text-slate-800 text-right">{{ $nikMasked }}</dd>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">Tanggal Terbit</dt>
                        <dd class="font-semibold text-slate-800 text-right">
                            {{ $surat->tanggal_surat ? $surat->tanggal_surat->translatedFormat('d F Y') : '-' }}
                        </dd>
                    </div>
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <dt class="text-xs font-medium text-slate-500 uppercase">Status</dt>
                        <dd class="text-right">
                            @if ($isValid)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Selesai / Terbit
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ ucfirst($surat->status) }}
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <p class="text-xs text-slate-500 mt-4 leading-relaxed">
                Halaman ini menampilkan data surat yang tercatat pada sistem informasi
                {{ $desa->nama_desa ?? 'Desa Saobi' }}. Jika data tidak sesuai dengan dokumen fisik
                yang Anda terima, harap segera menghubungi kantor desa.
            </p>
        </main>

        <footer class="bg-white border-t border-slate-200 py-5 text-center text-xs text-slate-500">
            <div class="max-w-3xl mx-auto px-4">
                &copy; {{ date('Y') }} <strong>{{ $desa->nama_desa ?? 'Pemerintah Desa Saobi' }}</strong>@if (filled($desa?->wilayahLengkap())), {{ $desa->wilayahLengkap() }}@endif.
            </div>
        </footer>
    </div>
</body>
</html>
