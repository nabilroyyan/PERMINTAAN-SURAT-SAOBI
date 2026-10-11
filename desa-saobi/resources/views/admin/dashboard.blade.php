<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Dashboard Administrator Desa Saobi
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Panel kendali layanan surat administrasi dan kependudukan warga Desa Saobi
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.settings.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    </svg>
                    Profil Kades & TTD
                </a>
                <a href="{{ route('admin.permohonan.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Semua Permohonan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Permohonan -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex items-center justify-between hover:border-emerald-300 transition">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Permohonan</p>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $total }}</p>
                    <p class="text-xs text-slate-400 mt-1">Keseluruhan surat masuk</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>

            <!-- Menunggu Tindakan -->
            <div class="bg-white rounded-2xl p-6 border border-amber-200 shadow-sm flex items-center justify-between hover:border-amber-300 transition">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Menunggu Verifikasi</p>
                    <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $pending }}</p>
                    <p class="text-xs text-amber-700 mt-1">Perlu tindakan admin</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <!-- Sedang Diproses -->
            <div class="bg-white rounded-2xl p-6 border border-blue-200 shadow-sm flex items-center justify-between hover:border-blue-300 transition">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Sedang Diproses</p>
                    <p class="text-3xl font-extrabold text-blue-600 mt-2">{{ $processing }}</p>
                    <p class="text-xs text-blue-500 mt-1">Dalam pembuatan surat</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>

            <!-- Selesai Diterbitkan -->
            <div class="bg-white rounded-2xl p-6 border border-emerald-200 shadow-sm flex items-center justify-between hover:border-emerald-400 transition">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Selesai / Terbit</p>
                    <p class="text-3xl font-extrabold text-emerald-700 mt-2">{{ $completed }}</p>
                    <p class="text-xs text-emerald-600 mt-1">Sudah terkirim ke warga</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tabel Permohonan Terbaru yang Perlu Diproses -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Permohonan Surat Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar 10 permohonan surat masuk paling baru</p>
                </div>
                <a href="{{ route('admin.permohonan.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition">
                    Kelola Semua &rarr;
                </a>
            </div>

            @if ($terbaru->isEmpty())
                <div class="p-12 text-center text-slate-500 text-sm">
                    Belum ada permohonan surat yang masuk.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Waktu Masuk</th>
                                <th class="px-6 py-4">Nama Warga</th>
                                <th class="px-6 py-4">NIK</th>
                                <th class="px-6 py-4">Jenis Surat</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($terbaru as $item)
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-6 py-4 text-slate-700 whitespace-nowrap text-xs font-medium">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $item->user->name ?? ($item->data_pemohon['nama'] ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                        {{ $item->user->nik ?? ($item->data_pemohon['nik'] ?? '-') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-800 font-semibold">
                                        {{ $item->suratType->nama ?? 'Surat Keterangan' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif (in_array($item->status, ['processing', 'revision']))
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                Diproses
                                            </span>
                                        @elseif ($item->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Selesai / Terbit
                                            </span>
                                        @elseif ($item->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('admin.permohonan.show', $item) }}"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                            Proses Surat &rarr;
                                        </a>
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
