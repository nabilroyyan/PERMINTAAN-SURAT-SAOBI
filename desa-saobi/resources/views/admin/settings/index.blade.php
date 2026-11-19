<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Pengaturan Profil Desa & Kepala Desa
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Atur nama kepala desa, NIP, alamat kop surat, dan tanda tangan digital untuk pencetakan surat otomatis
                </p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Dashboard Admin
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- Header Kartu -->
            <div class="p-4 sm:p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-emerald-100 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Profil Pemerintahan Desa Saobi</h3>
                    <p class="text-xs text-slate-600">Informasi ini akan otomatis tercetak pada Kop dan bagian Tanda Tangan seluruh Surat PDF resmi.</p>
                </div>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="p-4 mx-4 sm:mx-6 mt-4 sm:mt-6 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold mb-1 text-xs sm:text-sm">Periksa kembali data yang dimasukkan:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
                @csrf

                <!-- Nama Desa -->
                <div>
                    <label for="nama_desa" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Nama Desa <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="nama_desa"
                           id="nama_desa"
                           value="{{ old('nama_desa', $setting->nama_desa) }}"
                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3"
                           required>
                    @error('nama_desa')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Data Wilayah -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <label for="kecamatan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kecamatan
                        </label>
                        <input type="text"
                               name="kecamatan"
                               id="kecamatan"
                               value="{{ old('kecamatan', $setting->kecamatan) }}"
                               placeholder="Contoh: Kangayan"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        @error('kecamatan')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kabupaten" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Kabupaten
                        </label>
                        <input type="text"
                               name="kabupaten"
                               id="kabupaten"
                               value="{{ old('kabupaten', $setting->kabupaten) }}"
                               placeholder="Contoh: Sumenep"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        @error('kabupaten')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="provinsi" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Provinsi
                        </label>
                        <input type="text"
                               name="provinsi"
                               id="provinsi"
                               value="{{ old('provinsi', $setting->provinsi) }}"
                               placeholder="Contoh: Jawa Timur"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        @error('provinsi')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    <!-- Nama Kepala Desa -->
                    <div>
                        <label for="nama_kepala_desa" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            Nama Lengkap Kepala Desa <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_kepala_desa"
                               id="nama_kepala_desa"
                               value="{{ old('nama_kepala_desa', $setting->nama_kepala_desa) }}"
                               placeholder="Contoh: MOH. SALEH, S.Pd"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-semibold"
                               required>
                        @error('nama_kepala_desa')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP Kepala Desa -->
                    <div>
                        <label for="nip_kepala_desa" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                            NIP Kepala Desa (Opsional)
                        </label>
                        <input type="text"
                               name="nip_kepala_desa"
                               id="nip_kepala_desa"
                               value="{{ old('nip_kepala_desa', $setting->nip_kepala_desa) }}"
                               placeholder="Contoh: 198205122010011005 atau tanda - jika non-PNS"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono">
                        @error('nip_kepala_desa')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Alamat Kantor Desa (Kop Surat) -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Alamat Kantor Desa (Untuk Baris Kop Surat) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat"
                              id="alamat"
                              rows="3"
                              class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3"
                              required>{{ old('alamat', $setting->alamat) }}</textarea>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Alamat ini akan ditampilkan tepat di bawah kop surat resmi.</p>
                    @error('alamat')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mt-4">
                        <div>
                            <label for="kode_pos" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $setting->kode_pos) }}"
                                   placeholder="Contoh: 69491"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono">
                        </div>
                        <div>
                            <label for="telepon" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Telepon Kantor</label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $setting->telepon) }}"
                                   placeholder="Contoh: 0812xxxxxxx"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Email Desa</label>
                            <input type="text" name="email" id="email" value="{{ old('email', $setting->email) }}"
                                   placeholder="Contoh: desasaobi@gmail.com"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        </div>
                        <div>
                            <label for="website" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Website (Opsional)</label>
                            <input type="text" name="website" id="website" value="{{ old('website', $setting->website) }}"
                                   placeholder="Contoh: desasaobi.desa.id"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3">
                        </div>
                    </div>
                </div>

                <!-- Logo Kop Surat (Kiri & Kanan) -->
                <div class="border-t border-slate-100 pt-5 sm:pt-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Logo Kop Surat (Kiri &amp; Kanan)
                    </label>
                    <p class="text-xs text-slate-500 mb-3">Logo tampil di sisi kiri dan kanan kop surat PDF. Gunakan PNG dengan latar transparan.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="logo_kiri" class="block text-xs font-semibold text-slate-600 mb-1.5">Logo Kiri</label>
                            @if ($setting->logo_kiri)
                                <div class="mb-3 p-3 rounded-xl bg-slate-50 border border-slate-200 inline-block">
                                    <img src="{{ asset('storage/' . $setting->logo_kiri) }}" alt="Logo Kiri"
                                         class="h-16 object-contain bg-white p-1 rounded-lg border border-slate-200">
                                </div>
                            @endif
                            <input type="file" name="logo_kiri" id="logo_kiri" accept="image/png,image/jpeg,image/jpg"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-white border border-slate-200 rounded-xl p-1.5 transition">
                            @error('logo_kiri')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="logo_kanan" class="block text-xs font-semibold text-slate-600 mb-1.5">Logo Kanan</label>
                            @if ($setting->logo_kanan)
                                <div class="mb-3 p-3 rounded-xl bg-slate-50 border border-slate-200 inline-block">
                                    <img src="{{ asset('storage/' . $setting->logo_kanan) }}" alt="Logo Kanan"
                                         class="h-16 object-contain bg-white p-1 rounded-lg border border-slate-200">
                                </div>
                            @endif
                            <input type="file" name="logo_kanan" id="logo_kanan" accept="image/png,image/jpeg,image/jpg"
                                   class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-white border border-slate-200 rounded-xl p-1.5 transition">
                            @error('logo_kanan')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Upload Tanda Tangan & Stempel -->
                <div class="border-t border-slate-100 pt-5 sm:pt-6">
                    <label for="gambar_tanda_tangan" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                        Scan Tanda Tangan Kepala Desa & Stempel Desa (PNG / JPG)
                    </label>
                    <p class="text-xs text-slate-500 mb-3">Disarankan file berekstensi PNG dengan latar belakang transparan agar hasil cetak surat sangat rapi.</p>

                    @if ($setting->gambar_tanda_tangan)
                        <div class="mb-4 p-3 sm:p-4 rounded-xl bg-slate-50 border border-slate-200 inline-block max-w-full">
                            <p class="text-xs font-bold text-slate-600 mb-2">Tanda Tangan Saat Ini:</p>
                            <img src="{{ asset('storage/' . $setting->gambar_tanda_tangan) }}"
                                 alt="Tanda Tangan Kades"
                                 class="h-16 sm:h-20 object-contain bg-white p-2 rounded-lg border border-slate-200 max-w-full">
                        </div>
                    @endif

                    <input type="file"
                           name="gambar_tanda_tangan"
                           id="gambar_tanda_tangan"
                           accept="image/png,image/jpeg,image/jpg"
                           class="block w-full text-xs sm:text-sm text-slate-500 file:mr-3 sm:file:mr-4 file:py-2 sm:file:py-2.5 file:px-3 sm:file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-white border border-slate-200 rounded-xl p-1.5 transition">
                    @error('gambar_tanda_tangan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Simpan (Responsive on Mobile) -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pengaturan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
