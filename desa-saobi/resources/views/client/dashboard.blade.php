<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Dashboard Warga
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Selamat datang, <span class="font-semibold text-emerald-700">{{ Auth::user()->name }}</span> (NIK: {{ Auth::user()->nik ?? '-' }})
                </p>
            </div>
            <div>
                <a href="{{ route('client.surat.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Permohonan Surat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Banner Informasi Desa Saobi -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-teal-600 p-6 sm:p-8 text-white shadow-lg shadow-emerald-900/10">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-semibold text-emerald-100 uppercase tracking-wider mb-3">
                    Pelayanan Administrasi Digital
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Layanan Permohonan Surat Desa Saobi
                </h1>
                <p class="mt-2 text-emerald-100 text-sm sm:text-base leading-relaxed">
                    Ajukan permohonan surat administrasi desa secara online tanpa perlu antre di balai desa. Pantau status verifikasi dan langsung unduh surat resmi bertanda tangan digital.
                </p>
            </div>
            <div class="absolute -right-12 -bottom-12 opacity-15 pointer-events-none">
                <svg class="w-80 h-80" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Kartu Statistik Status Surat -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Permohonan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-emerald-300 transition">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total Pengajuan</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $total }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-amber-300 transition">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-amber-600">Menunggu Verifikasi</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $pending }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Sedang Diproses -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-blue-300 transition">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-blue-600">Sedang Diproses</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $processing }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
            </div>

            <!-- Selesai (Bisa Diunduh) -->
            <div class="bg-white rounded-2xl p-6 border border-emerald-200 shadow-sm flex items-center justify-between hover:border-emerald-400 transition">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-700">Selesai (Siap Unduh)</p>
                    <p class="text-3xl font-extrabold text-emerald-700 mt-2">{{ $completed }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tabel Permohonan Surat Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Riwayat Pengajuan Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pantau status persetujuan surat Anda di bawah ini</p>
                </div>
                <a href="{{ route('client.surat.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if ($suratTerbaru->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-slate-800 font-semibold text-base">Belum Ada Permohonan Surat</h4>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                        Anda belum pernah membuat permohonan surat. Silakan klik tombol di bawah untuk mulai mengajukan.
                    </p>
                    <div class="mt-5">
                        <a href="{{ route('client.surat.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl transition">
                            Ajukan Surat Sekarang
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Jenis Surat</th>
                                <th class="px-6 py-4">Nomor Surat</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($suratTerbaru as $item)
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-6 py-4 font-medium text-slate-800 whitespace-nowrap">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $item->suratType->nama ?? 'Surat Keterangan' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                        {{ $item->nomor_surat ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 me-1.5"></span> Menunggu
                                            </span>
                                        @elseif (in_array($item->status, ['processing', 'revision']))
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 me-1.5"></span> Diproses
                                            </span>
                                        @elseif ($item->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Selesai
                                            </span>
                                        @elseif ($item->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 me-1.5"></span> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('client.surat.show', $item) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                                            Lihat Status
                                        </a>

                                        @if ($item->status === 'completed' && $item->file_surat)
                                            <a href="{{ route('client.surat.download', $item) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Unduh PDF
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
