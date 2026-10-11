<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Tambah Data Warga Manual
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Tambahkan data kependudukan satu per satu ke dalam database resmi Desa Saobi
                </p>
            </div>
            <div>
                <a href="{{ route('admin.penduduk.index') }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Kembali ke Master Penduduk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-emerald-100 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Formulir Pendaftaran Warga Baru</h3>
                    <p class="text-xs text-slate-600">Pastikan NIK dan Nomor KK sesuai dengan dokumen fisik Kartu Keluarga warga.</p>
                </div>
            </div>

            <form action="{{ route('admin.penduduk.store') }}" method="POST" class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nomor Induk Kependudukan (NIK 16 Digit) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nik"
                               id="nik"
                               value="{{ old('nik') }}"
                               maxlength="16"
                               placeholder="3529xxxxxxxxxxxx"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono"
                               required>
                        @error('nik')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No KK -->
                    <div>
                        <label for="no_kk" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nomor Kartu Keluarga (No. KK 16 Digit) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="no_kk"
                               id="no_kk"
                               value="{{ old('no_kk') }}"
                               maxlength="16"
                               placeholder="3529xxxxxxxxxxxx"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono"
                               required>
                        @error('no_kk')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Nama Lengkap Sesuai KTP <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_lengkap"
                           id="nama_lengkap"
                           value="{{ old('nama_lengkap') }}"
                           placeholder="Contoh: AHMAD MAULANA"
                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-semibold uppercase"
                           required>
                    @error('nama_lengkap')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Tempat Lahir
                        </label>
                        <input type="text"
                               name="tempat_lahir"
                               id="tempat_lahir"
                               value="{{ old('tempat_lahir', 'Sumenep') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        @error('tempat_lahir')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Tanggal Lahir
                        </label>
                        <input type="date"
                               name="tanggal_lahir"
                               id="tanggal_lahir"
                               value="{{ old('tanggal_lahir') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        @error('tanggal_lahir')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label for="jenis_kelamin" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin"
                                id="jenis_kelamin"
                                class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3"
                                required>
                            <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Dusun -->
                    <div>
                        <label for="dusun" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Dusun</label>
                        <input type="text"
                               name="dusun"
                               id="dusun"
                               value="{{ old('dusun') }}"
                               placeholder="Contoh: Saobi Timur"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>
                    <!-- RT -->
                    <div>
                        <label for="rt" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">RT</label>
                        <input type="text"
                               name="rt"
                               id="rt"
                               value="{{ old('rt') }}"
                               placeholder="01"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>
                    <!-- RW -->
                    <div>
                        <label for="rw" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">RW</label>
                        <input type="text"
                               name="rw"
                               id="rw"
                               value="{{ old('rw') }}"
                               placeholder="02"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat"
                              id="alamat"
                              rows="2"
                              placeholder="Dusun Saobi Timur RT 01 / RW 02, Desa Saobi, Kec. Kangayan"
                              class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3">{{ old('alamat') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Pekerjaan -->
                    <div>
                        <label for="pekerjaan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Pekerjaan</label>
                        <input type="text"
                               name="pekerjaan"
                               id="pekerjaan"
                               value="{{ old('pekerjaan') }}"
                               placeholder="Contoh: Nelayan / Petani / Wiraswasta"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>
                    <!-- No HP -->
                    <div>
                        <label for="telepon" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Nomor HP / WhatsApp</label>
                        <input type="text"
                               name="telepon"
                               id="telepon"
                               value="{{ old('telepon') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                    </div>
                </div>

                <!-- Bagian Akun Login Online (Opsional) -->
                <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Buat Akun Login Langsung (Opsional)
                        </h4>
                        <p class="text-xs text-slate-500 mt-0.5">Isi jika warga ingin langsung bisa login tanpa perlu registrasi mandiri.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email Akun</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   placeholder="warga@email.com"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3">
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Password (Default: 12345678)</label>
                            <input type="text"
                                   name="password"
                                   id="password"
                                   value="{{ old('password') }}"
                                   placeholder="Kosongkan untuk default: 12345678"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3">
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit (Responsive Stack on Mobile) -->
                <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 sm:gap-3">
                    <a href="{{ route('admin.penduduk.index') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs sm:text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data Warga
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
