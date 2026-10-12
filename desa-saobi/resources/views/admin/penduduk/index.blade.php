<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Master Data Kependudukan Desa Saobi
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola data resmi warga, import database kependudukan, dan atur hak akses / role akun (Warga, Admin, Kepala Desa)
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                <a href="{{ route('admin.penduduk.sample-5000') }}"
                   title="Unduh 5.000 data kependudukan siap pakai Desa Saobi untuk pengujian sistem"
                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Data 5.000 Penduduk (.xlsx)
                </a>
                <a href="{{ route('admin.penduduk.template') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Template Excel
                </a>
                <a href="{{ route('admin.penduduk.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    + Tambah Warga
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
         x-data="{
             accountModal: false,
             activeId: null,
             activeNama: '',
             activeNik: '',
             activeEmail: '',
             activeRole: 'warga',
             activePassword: '',
             formAction: '',

             openAccountModal(data) {
                 this.activeId = data.id;
                 this.activeNama = data.nama;
                 this.activeNik = data.nik;
                 this.activeEmail = data.email || (data.nik + '@saobi.desa.id');
                 this.activeRole = data.role || 'warga';
                 this.activePassword = data.password || '';
                 this.formAction = '{{ url('admin/penduduk') }}/' + data.id + '/account';
                 this.accountModal = true;
             }
         }">
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
                            <span class="text-xl font-extrabold text-emerald-700">{{ number_format($totalAkun) }} Akun</span>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">
                    Setiap administrator dan kepala desa merupakan penduduk Desa Saobi. Anda dapat mengatur role akun penduduk langsung pada tabel di bawah.
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
                        <h3 class="text-base font-bold text-slate-800">Import Data Warga dari File Excel (.xlsx / .csv)</h3>
                        <p class="text-xs text-slate-600">Sistem mendukung import massal hingga 5.000+ data penduduk berkecepatan tinggi (10-15 detik) dengan otomatisasi pembuatan akun & sinkronisasi NIK.</p>
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
                        <div class="text-xs text-slate-600 space-y-1">
                            <div>Format kolom: <a href="{{ route('admin.penduduk.template') }}" class="font-bold text-emerald-700 underline">Unduh Template Excel Kosong</a></div>
                            <div>Butuh data uji coba? <a href="{{ route('admin.penduduk.sample-5000') }}" class="font-bold text-emerald-800 underline">Unduh Data Simulasi 5.000 Penduduk Saobi (.xlsx)</a></div>
                        </div>
                        <button type="submit"
                                onclick="return confirm('Mulai proses import data penduduk? Sistem akan memproses dan mencocokkan data secara massal.')"
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
                <div class="sm:col-span-8 lg:col-span-7">
                    <label for="q" class="block text-xs font-bold text-slate-600 uppercase mb-1">Cari Data Warga</label>
                    <div class="relative">
                        <input type="text"
                               name="q"
                               id="q"
                               value="{{ request('q') }}"
                               placeholder="Cari nama, NIK (16 digit), No KK, atau nama Dusun..."
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3 ps-9">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="sm:col-span-4 lg:col-span-3">
                    <label for="status_akun" class="block text-xs font-bold text-slate-600 uppercase mb-1">Status Akun Login</label>
                    <select name="status_akun"
                            id="status_akun"
                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
                        <option value="">Semua Status</option>
                        <option value="terdaftar" {{ request('status_akun') === 'terdaftar' ? 'selected' : '' }}>Sudah Memiliki Akun</option>
                        <option value="belum" {{ request('status_akun') === 'belum' ? 'selected' : '' }}>Belum Memiliki Akun</option>
                    </select>
                </div>

                <div class="sm:col-span-12 lg:col-span-2 flex items-end gap-2 pt-1 sm:pt-0 sm:mt-5">
                    <button type="submit"
                            class="flex-1 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                        Filter
                    </button>
                    @if (request()->hasAny(['q', 'status_akun']))
                        <a href="{{ route('admin.penduduk.index') }}"
                           class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition"
                           title="Reset filter">
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
                                <th class="px-5 py-4">No</th>
                                <th class="px-5 py-4">Nama Lengkap</th>
                                <th class="px-5 py-4">NIK & No. KK</th>
                                <th class="px-5 py-4">L/P</th>
                                <th class="px-5 py-4">Dusun / Alamat</th>
                                <th class="px-5 py-4">Email Akun</th>
                                <th class="px-5 py-4">Role Sistem</th>
                                <th class="px-5 py-4">Password</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($penduduks as $index => $item)
                                @php
                                    $userObj = $item->user;
                                    $roleValue = $userObj ? ($userObj->role instanceof \App\Enums\UserRole ? $userObj->role->value : (string)$userObj->role) : 'warga';
                                @endphp
                                <tr class="hover:bg-emerald-50/40 transition">
                                    <td class="px-5 py-4 font-mono text-xs text-slate-400">
                                        {{ $penduduks->firstItem() + $index }}
                                    </td>
                                    <td class="px-5 py-4 font-bold text-slate-800">
                                        {{ $item->nama_lengkap }}
                                        <div class="text-[11px] text-slate-400 font-normal">
                                            {{ $item->tempat_lahir ?? '-' }}, {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 font-mono text-xs">
                                        <div class="font-bold text-slate-800">{{ $item->nik }}</div>
                                        <div class="text-slate-400">{{ $item->no_kk }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-xs font-medium">
                                        <span class="px-2 py-0.5 rounded-md {{ $item->jenis_kelamin === 'Laki-laki' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $item->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs max-w-xs truncate">
                                        {{ $item->dusun ? 'Dusun ' . $item->dusun : ($item->alamat ?? '-') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs">
                                        @if ($userObj)
                                            <span class="font-mono text-slate-700 font-semibold select-all">{{ $userObj->email }}</span>
                                        @else
                                            <span class="text-slate-400 italic">Belum dibuat</span>
                                        @endif
                                    </td>
                                    <!-- Role Sistem Badge -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($userObj)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $userObj->role_badge_class }}">
                                                {{ $userObj->role_label }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                                Belum Ada Akun
                                            </span>
                                        @endif
                                    </td>
                                    <!-- Password Akun -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($userObj)
                                            <span class="font-mono text-xs px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold select-all" title="Password akun">
                                                {{ $userObj->password_plain ?? '••••••••' }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <!-- Aksi: Edit Role & Hapus -->
                                    <td class="px-5 py-4 text-right whitespace-nowrap space-x-1">
                                        <!-- Tombol Atur Akun & Role -->
                                        <button type="button"
                                                @click="openAccountModal({
                                                    id: {{ $item->id }},
                                                    nama: '{{ addslashes($item->nama_lengkap) }}',
                                                    nik: '{{ $item->nik }}',
                                                    email: '{{ $userObj ? addslashes($userObj->email) : '' }}',
                                                    role: '{{ $roleValue }}',
                                                    password: '{{ $userObj ? addslashes($userObj->password_plain ?? '') : '' }}'
                                                })"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition"
                                                title="Atur Hak Akses / Role (Warga, Admin, Kepala Desa)">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                            <span>Atur Role</span>
                                        </button>

                                        <!-- Tombol Hapus -->
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

        <!-- ================= MODAL ATUR AKUN & ROLE PENDUDUK ================= -->
        <div x-show="accountModal"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="accountModal = false"
                 class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden transform transition-all p-6 sm:p-7 space-y-5">
                <!-- Header Modal -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Atur Akun & Role Penduduk</h3>
                            <p class="text-xs text-slate-500">Tentukan hak akses untuk penduduk Desa Saobi</p>
                        </div>
                    </div>
                    <button type="button" @click="accountModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Info Penduduk Terpilih -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Identitas Warga</p>
                    <p class="text-sm font-extrabold text-slate-800 mt-0.5" x-text="activeNama"></p>
                    <p class="text-xs font-mono text-emerald-700 font-semibold mt-0.5">NIK: <span x-text="activeNik"></span></p>
                </div>

                <!-- Form Ubah Role & Akun -->
                <form :action="formAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Pilihan Role (Warga, Admin, Kepala Desa) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">
                            Pilih Hak Akses / Role Sistem:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                            <!-- Warga -->
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="warga" x-model="activeRole" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-slate-200 text-center peer-checked:border-emerald-600 peer-checked:bg-emerald-50/70 peer-checked:text-emerald-900 transition">
                                    <div class="text-xs font-bold">Warga</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Layanan Surat</div>
                                </div>
                            </label>

                            <!-- Administrator -->
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="admin" x-model="activeRole" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-slate-200 text-center peer-checked:border-purple-600 peer-checked:bg-purple-50/70 peer-checked:text-purple-900 transition">
                                    <div class="text-xs font-bold">Admin</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Kelola Sistem</div>
                                </div>
                            </label>

                            <!-- Kepala Desa -->
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="kepala_desa" x-model="activeRole" class="peer sr-only">
                                <div class="p-3 rounded-xl border border-slate-200 text-center peer-checked:border-amber-600 peer-checked:bg-amber-50/70 peer-checked:text-amber-900 transition">
                                    <div class="text-xs font-bold">Kepala Desa</div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Pengesahan / TTD</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Email Akun -->
                    <div>
                        <label for="modal_email" class="block text-xs font-bold text-slate-700 mb-1">
                            Alamat Email Login <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="modal_email"
                               x-model="activeEmail"
                               required
                               placeholder="nama@saobi.desa.id"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label for="modal_password" class="block text-xs font-bold text-slate-700 mb-1">
                            Kata Sandi (Password)
                        </label>
                        <input type="text"
                               name="password"
                               id="modal_password"
                               x-model="activePassword"
                               placeholder="Masukkan sandi baru (kosongkan jika tidak diubah)"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono">
                        <p class="text-[11px] text-slate-400 mt-1">
                            Jika belum memiliki akun, default adalah <code class="font-bold text-slate-600">saobi123</code>.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button type="button"
                                @click="accountModal = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Perubahan Role & Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
