<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Riwayat Permohonan Surat
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar seluruh surat administrasi yang pernah Anda ajukan ke Pemerintah Desa Saobi
                </p>
            </div>
            <div>
                <a href="{{ route('client.surat.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-emerald-600/20 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Surat Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if ($surat->isEmpty())
                <div class="p-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Surat</h3>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto">
                        Anda belum memiliki riwayat pengajuan surat. Silakan mulai mengajukan surat permohonan pertama Anda.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('client.surat.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            Ajukan Permohonan Surat
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Jenis Surat</th>
                                <th class="px-6 py-4">Nomor Surat</th>
                                <th class="px-6 py-4">Keperluan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($surat as $index => $item)
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ $surat->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-800 font-medium">
                                        {{ $item->created_at->translatedFormat('d F Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $item->suratType->nama ?? 'Surat Keterangan' }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                        {{ $item->nomor_surat ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-600 max-w-xs truncate">
                                        {{ $item->data_pemohon['keperluan'] ?? '-' }}
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
                                            Detail & Tracking
                                        </a>

                                        @if ($item->status === 'completed' && $item->file_surat)
                                            <a href="{{ route('client.surat.download', $item) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
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

                @if ($surat->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $surat->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
