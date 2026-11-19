<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Isi & Badan Surat
                    </span>
                    <span class="text-xs text-slate-400">•</span>
                    <span class="text-xs text-slate-500 font-medium">Template Dinamis</span>
                </div>
                <h2 class="font-extrabold text-2xl text-slate-800 tracking-tight mt-1">
                    Template Isi Surat
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Buat dan kelola template isi surat. Setiap template dapat dipakai oleh satu atau beberapa jenis surat.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.template-surat.create') }}"
                   class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Template
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6 px-3 sm:px-6 lg:px-8">

        <div class="bg-gradient-to-r from-emerald-50 via-teal-50/50 to-white p-5 rounded-2xl border border-emerald-200/80 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-xs text-slate-600 space-y-1">
                    <h4 class="font-bold text-slate-800 text-sm">Cara Kerja Template Surat Dinamis:</h4>
                    <p>
                        Tulis isi surat dengan menyisipkan <strong>variabel</strong> seperti
                        <code class="font-mono text-emerald-800">{nama}</code>,
                        <code class="font-mono text-emerald-800">{nik}</code>, atau
                        <code class="font-mono text-emerald-800">{nomor_surat}</code>.
                        Saat surat diterbitkan, variabel otomatis diganti dengan data sebenarnya. Hubungkan template ke
                        <a href="{{ route('admin.jenis-surat.index') }}" class="font-bold text-emerald-700 hover:underline">Jenis Surat</a>
                        agar dipakai pada penerbitan surat.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Nama Template</th>
                            <th class="px-6 py-4">Digunakan Jenis Surat</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($templates as $item)
                            <tr class="hover:bg-emerald-50/30 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm">{{ $item->nama }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5 max-w-md truncate">
                                        {{ $item->deskripsi ?? 'Tidak ada deskripsi' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($item->surat_types_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                                            {{ $item->surat_types_count }} jenis surat
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum dipakai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($item->is_active)
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.template-surat.edit', $item) }}"
                                           class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-xs rounded-lg transition border border-emerald-200 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit Isi
                                        </a>

                                        @if ($item->surat_types_count == 0)
                                            <form action="{{ route('admin.template-surat.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus template ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada template isi surat. Silakan buat template terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
