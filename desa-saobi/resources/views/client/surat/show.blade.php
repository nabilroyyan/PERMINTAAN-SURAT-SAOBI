<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Detail & Status Permohonan Surat
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    ID Permohonan: <span class="font-mono font-semibold text-slate-700">#{{ $suratRequest->id }}</span> &bull; Diajukan pada {{ $suratRequest->created_at->translatedFormat('d F Y, H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('client.surat.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Riwayat Surat
                </a>

                @if ($suratRequest->status === 'completed' && $suratRequest->file_surat)
                    <a href="{{ route('client.surat.download', $suratRequest) }}"
                       class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-emerald-600/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Unduh Surat Resmi (PDF)
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Status Banner -->
        @if ($suratRequest->status === 'completed')
            <div class="rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-white shadow-md shadow-emerald-700/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Surat Telah Diterbitkan Resmi!</h3>
                        <p class="text-sm text-emerald-100">
                            Nomor Surat: <strong>{{ $suratRequest->nomor_surat }}</strong> &bull; Tanggal: {{ $suratRequest->tanggal_surat ? $suratRequest->tanggal_surat->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('client.surat.download', $suratRequest) }}"
                   class="px-5 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 font-bold text-sm rounded-xl shadow-sm transition">
                    Unduh Sekarang
                </a>
            </div>
        @elseif ($suratRequest->status === 'rejected')
            <div class="rounded-2xl bg-red-50 border border-red-200 p-6 text-red-800 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold">Permohonan Surat Ditolak</h3>
                    <p class="text-sm mt-1">Alasan Penolakan: <strong>{{ $suratRequest->catatan_admin ?? 'Berkas atau data tidak memenuhi syarat.' }}</strong></p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Detail Surat (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Rincian Permohonan Surat
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Jenis Surat</dt>
                            <dd class="mt-1 text-base font-bold text-slate-800">
                                {{ $suratRequest->suratType->nama ?? 'Surat Keterangan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Status Permohonan</dt>
                            <dd class="mt-1">
                                @if ($suratRequest->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        Menunggu Verifikasi Admin
                                    </span>
                                @elseif (in_array($suratRequest->status, ['processing', 'revision']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        Sedang Diproses Desa
                                    </span>
                                @elseif ($suratRequest->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Surat Selesai Diterbitkan
                                    </span>
                                @elseif ($suratRequest->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                        Permohonan Ditolak
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Nama Pemohon</dt>
                            <dd class="mt-1 font-semibold text-slate-800">
                                {{ $suratRequest->data_pemohon['nama'] ?? '-' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">NIK Pemohon</dt>
                            <dd class="mt-1 font-mono font-semibold text-slate-800">
                                {{ $suratRequest->data_pemohon['nik'] ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Alamat Domisili</dt>
                            <dd class="mt-1 text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ $suratRequest->data_pemohon['alamat'] ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Keperluan Permohonan</dt>
                            <dd class="mt-1 text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ $suratRequest->data_pemohon['keperluan'] ?? '-' }}
                            </dd>
                        </div>

                        @if ($suratRequest->catatan_admin)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-amber-700 uppercase tracking-wider">Catatan Petugas Admin</dt>
                                <dd class="mt-1 text-amber-900 bg-amber-50 p-3 rounded-xl border border-amber-200 text-xs">
                                    {{ $suratRequest->catatan_admin }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Kolom Timeline Riwayat Status (1 Kolom) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tracking Riwayat Status
                    </h3>

                    <div class="relative border-l-2 border-emerald-200 ms-3 space-y-6">
                        @foreach ($suratRequest->statusHistories as $history)
                            <div class="ms-6 relative">
                                <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-emerald-600 ring-4 ring-emerald-50"></span>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                    Status: {{ strtoupper($history->status) }}
                                </h4>
                                <p class="text-xs text-slate-400 mt-0.5 font-medium">
                                    {{ $history->created_at->translatedFormat('d M Y, H:i') }}
                                </p>
                                @if ($history->catatan)
                                    <p class="text-xs text-slate-600 mt-1 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        {{ $history->catatan }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
