<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Kelola Permohonan Surat
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Verifikasi berkas, tentukan status, dan terbitkan surat resmi warga Desa Saobi
                </p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Dashboard Admin
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <form method="GET" action="{{ route('admin.permohonan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                <!-- Search Keyword -->
                <div class="sm:col-span-6 lg:col-span-5">
                    <label for="q" class="block text-xs font-bold text-slate-600 uppercase mb-1">Cari Permohonan</label>
                    <div class="relative">
                        <input type="text"
                               name="q"
                               id="q"
                               value="{{ request('q') }}"
                               placeholder="Cari nama pemohon, NIK, atau nomor surat..."
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3 ps-9">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-4 lg:col-span-4">
                    <label for="status" class="block text-xs font-bold text-slate-600 uppercase mb-1">Filter Status</label>
                    <select name="status"
                            id="status"
                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu Verifikasi</option>
                        <option value="processing" @selected(request('status') === 'processing')>Sedang Diproses</option>
                        <option value="completed" @selected(request('status') === 'completed')>Selesai / Terbit</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    </select>
                </div>

                <!-- Submit Filter Button -->
                <div class="sm:col-span-2 lg:col-span-3 flex items-end gap-2 pt-5 sm:pt-0">
                    <button type="submit"
                            class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        Filter
                    </button>
                    @if (request()->hasAny(['q', 'status']))
                        <a href="{{ route('admin.permohonan.index') }}"
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition"
                           title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data Permohonan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if ($permohonan->isEmpty())
                <div class="p-16 text-center text-slate-500 text-sm">
                    Tidak ditemukan data permohonan surat yang sesuai.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Waktu Masuk</th>
                                <th class="px-6 py-4">Nama Pemohon</th>
                                <th class="px-6 py-4">NIK</th>
                                <th class="px-6 py-4">Jenis Surat</th>
                                <th class="px-6 py-4">Nomor Surat</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($permohonan as $item)
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-6 py-4 text-slate-700 whitespace-nowrap text-xs">
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
                                    <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                        {{ $item->nomor_surat ?? '-' }}
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
                                            Proses &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($permohonan->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $permohonan->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
