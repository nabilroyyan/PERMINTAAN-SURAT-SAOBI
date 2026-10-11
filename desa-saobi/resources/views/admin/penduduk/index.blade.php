<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Master Data Kependudukan Desa Saobi
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola data resmi warga, import database kependudukan dari file Excel, dan pantau akun warga online
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                <a href="{{ route('admin.penduduk.template') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-white border border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Template Excel
                </a>
                <a href="{{ route('admin.penduduk.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    + Tambah Warga
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Statistik Ringkasan & Form Import Excel -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kartu Statistik -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Ringkasan Kependudukan
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-xs font-semibold text-slate-600">Total Warga Terdata:</span>
                            <span class="text-xl font-extrabold text-slate-800">{{ number_format($totalPenduduk) }} Orang</span>
                        </div>
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                            <span class="text-xs font-semibold text-emerald-800">Akun Terdaftar Online:</span>
                            <span class="text-xl font-extrabold text-emerald-700">{{ number_format($totalAkun) }} Warga</span>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">
                    Warga yang terdaftar di master data ini dapat langsung membuat akun login secara mandiri dengan verifikasi NIK & Nomor KK.
                </p>
            </div>

            <!-- Panel Upload / Import Excel -->
            <div class="lg:col-span-2 bg-gradient-to-br from-emerald-500/10 via-emerald-50/50 to-teal-50 rounded-2xl border border-emerald-200 shadow-sm p-6 sm:p-7">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Import Data Warga & Akun Login dari Excel (.xlsx / .csv)</h3>
                        <p class="text-xs text-slate-600">Tambahkan data warga beserta <strong>Email & Password</strong> dari website sebelumnya agar warga langsung bisa login tanpa registrasi.</p>
                    </div>
                </div>

                <form action="{{ route('admin.penduduk.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="file_excel" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Pilih File Excel Kependudukan
                        </label>
                        <input type="file"
                               name="file_excel"
                               id="file_excel"
                               accept=".xlsx,.xls,.csv"
                               required
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 bg-white rounded-xl border border-slate-300 p-1.5 shadow-sm transition">
                        @error('file_excel')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                        <span class="text-xs text-slate-500">
                            Belum tahu format kolomnya? <a href="{{ route('admin.penduduk.template') }}" class="font-bold text-emerald-700 underline">Unduh template di sini</a>.
                        </span>
                        <button type="submit"
                                onclick="return confirm('Mulai proses import data penduduk? Sistem akan mencocokkan NIK dan memperbarui data otomatis.')"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Mulai Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
            <form method="GET" action="{{ route('admin.penduduk.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                <!-- Search Keyword -->
                <div class="sm:col-span-6 lg:col-span-6">
                    <label for="q" class="block text-xs font-bold text-slate-600 uppercase mb-1">Cari Warga</label>
                    <div class="relative">
                        <input type="text"
                               name="q"
                               id="q"
                               value="{{ request('q') }}"
                               placeholder="Cari nama warga, NIK 16 digit, No KK, atau Dusun..."
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3 ps-9">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Status Akun Filter -->
                <div class="sm:col-span-3 lg:col-span-3">
                    <label for="status_akun" class="block text-xs font-bold text-slate-600 uppercase mb-1">Status Akun Login</label>
                    <select name="status_akun"
                            id="status_akun"
                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
                        <option value="">Semua Warga</option>
                        <option value="terdaftar" @selected(request('status_akun') === 'terdaftar')>Sudah Punya Akun</option>
                        <option value="belum" @selected(request('status_akun') === 'belum')>Belum Punya Akun</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="sm:col-span-3 lg:col-span-3 flex items-end gap-2 pt-5 sm:pt-0">
                    <button type="submit"
                            class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow-sm transition">
                        Filter
                    </button>
                    @if (request()->hasAny(['q', 'status_akun']))
                        <a href="{{ route('admin.penduduk.index') }}"
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition"
                           title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Data Penduduk -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if ($penduduks->isEmpty())
                <div class="p-16 text-center text-slate-500 text-sm">
                    Tidak ditemukan data kependudukan. Silakan import file Excel atau tambah data warga.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Nama Lengkap</th>
                                <th class="px-6 py-4">NIK</th>
                                <th class="px-6 py-4">No. KK</th>
                                <th class="px-6 py-4">L/P</th>
                                <th class="px-6 py-4">Tempat, Tgl Lahir</th>
                                <th class="px-6 py-4">Alamat / Dusun</th>
                                <th class="px-6 py-4">Email Akun</th>
                                <th class="px-6 py-4">Password</th>
                                <th class="px-6 py-4">Status Akun</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($penduduks as $index => $item)
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ $penduduks->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $item->nama_lengkap }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-800 font-semibold">
                                        {{ $item->nik }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                        {{ $item->no_kk }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium">
                                        <span class="px-2 py-0.5 rounded-md {{ $item->jenis_kelamin === 'Laki-laki' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $item->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs whitespace-nowrap">
                                        {{ $item->tempat_lahir ?? '-' }}, {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs truncate">
                                        {{ $item->alamat ?? ($item->dusun ? 'Dusun ' . $item->dusun : '-') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->user)
                                            <div class="flex items-center gap-1.5 font-medium text-xs text-slate-800">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                </svg>
                                                <span class="font-mono text-emerald-800 font-semibold select-all">{{ $item->user->email }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Belum dibuat</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->user)
                                            <span class="font-mono text-xs px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold select-all tracking-wide" title="Password akun warga">
                                                {{ $item->user->password_plain ?? 'saobi123' }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->user_id)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200" title="Warga memiliki akun login untuk layanan surat online">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200" title="Warga belum memiliki akun login">
                                                Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.penduduk.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data warga ini dari master database?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($penduduks->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $penduduks->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
