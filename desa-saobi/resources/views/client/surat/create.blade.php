<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Pengajuan Surat Mandiri
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Silakan isi data keperluan permohonan surat administrasi Desa Saobi
                </p>
            </div>
            <div>
                <a href="{{ route('client.dashboard') }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Kembali ke Dashboard
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Formulir Permohonan Surat Online</h3>
                    <p class="text-xs text-slate-600">Pastikan data diri Anda sudah sesuai dengan KTP & Kartu Keluarga (KK).</p>
                </div>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="p-4 mx-4 sm:mx-6 mt-4 sm:mt-6 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold mb-1 text-xs sm:text-sm">Periksa kembali isian formulir Anda:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('client.surat.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="formulirPermohonan(@js($fieldMap), @js($syaratMap), @js($pendudukDefaults), @js(old('tambahan', [])), '{{ old('surat_type_id') }}')"
                  class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
                @csrf

                <!-- Informasi Pemohon (Read Only) -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 sm:p-5">
                    <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2.5 sm:mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path>
                        </svg>
                        Data Identitas Pemohon
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div class="bg-white p-3 rounded-lg border border-slate-200/60">
                            <span class="block text-[11px] text-slate-400 font-medium uppercase tracking-wider">Nama Lengkap (KTP)</span>
                            <span class="font-bold text-slate-800 mt-0.5 block truncate">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200/60">
                            <span class="block text-[11px] text-slate-400 font-medium uppercase tracking-wider">Nomor Induk Kependudukan (NIK)</span>
                            <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ Auth::user()->nik ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Jenis Surat -->
                <div>
                    <label for="surat_type_id" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Pilih Jenis Surat Yang Diajukan <span class="text-red-500">*</span>
                    </label>
                    <select id="surat_type_id"
                            name="surat_type_id"
                            x-model="jenisSuratId"
                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm"
                            required>
                        <option value="">-- Pilih Salah Satu Jenis Surat --</option>
                        @foreach ($jenisSurat as $jenis)
                            <option value="{{ $jenis->id }}" @selected(old('surat_type_id') == $jenis->id)>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('surat_type_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Persyaratan Dokumen (info) -->
                <template x-if="activeSyarat.length > 0">
                    <div class="bg-amber-50/70 border border-amber-200/80 rounded-xl p-3.5 sm:p-4">
                        <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">Persyaratan Dokumen</h4>
                        <ul class="list-disc list-inside space-y-1 text-xs text-amber-900">
                            <template x-for="(syarat, i) in activeSyarat" :key="i">
                                <li x-text="syarat"></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <!-- Data Isian Dinamis Khusus Jenis Surat Terpilih -->
                <template x-if="activeFields.length > 0">
                    <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-xl p-3.5 sm:p-5">
                        <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"></path>
                            </svg>
                            Data untuk Surat Ini
                        </h4>
                        <p class="text-[11px] sm:text-xs text-slate-500 mb-3">
                            Isian berikut khusus untuk jenis surat yang Anda pilih. Data akan dicetak pada surat.
                        </p>

                        <div class="space-y-4">
                            <template x-for="field in activeFields" :key="field.kode">
                                <div>
                                    <label :for="'tambahan_' + field.kode" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                                        <span x-text="field.label"></span><span x-show="field.wajib" class="text-red-500"> *</span>
                                    </label>

                                    <!-- Teks Pendek -->
                                    <template x-if="field.tipe === 'text'">
                                        <input type="text" :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                               :placeholder="field.placeholder || ''"
                                               x-init="$el.value = nilaiField(field)"
                                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm bg-white">
                                    </template>

                                    <!-- Teks Panjang -->
                                    <template x-if="field.tipe === 'textarea'">
                                        <textarea :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                                  :placeholder="field.placeholder || ''" rows="3"
                                                  x-init="$el.value = nilaiField(field)"
                                                  class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3 transition shadow-sm bg-white"></textarea>
                                    </template>

                                    <!-- Tanggal -->
                                    <template x-if="field.tipe === 'date'">
                                        <input type="date" :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                               x-init="$el.value = nilaiField(field)"
                                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm bg-white">
                                    </template>

                                    <!-- Angka -->
                                    <template x-if="field.tipe === 'number'">
                                        <input type="number" :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                               :placeholder="field.placeholder || ''"
                                               x-init="$el.value = nilaiField(field)"
                                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm bg-white">
                                    </template>

                                    <!-- Dropdown -->
                                    <template x-if="field.tipe === 'select'">
                                        <select :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                                class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm bg-white">
                                            <option value="">-- Pilih --</option>
                                            <template x-for="opt in field.options" :key="opt">
                                                <option :value="opt" :selected="nilaiField(field) === opt" x-text="opt"></option>
                                            </template>
                                        </select>
                                    </template>

                                    <!-- Radio -->
                                    <template x-if="field.tipe === 'radio'">
                                        <div class="flex flex-wrap gap-x-5 gap-y-2 pt-1">
                                            <template x-for="opt in field.options" :key="opt">
                                                <label class="inline-flex items-center gap-2 text-xs sm:text-sm text-slate-700">
                                                    <input type="radio" :name="'tambahan[' + field.kode + ']'" :value="opt"
                                                           :checked="nilaiField(field) === opt"
                                                           class="text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                                    <span x-text="opt"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Checkbox -->
                                    <template x-if="field.tipe === 'checkbox'">
                                        <div class="flex flex-wrap gap-x-5 gap-y-2 pt-1">
                                            <template x-for="opt in field.options" :key="opt">
                                                <label class="inline-flex items-center gap-2 text-xs sm:text-sm text-slate-700">
                                                    <input type="checkbox" :name="'tambahan[' + field.kode + '][]'" :value="opt"
                                                           :checked="centangLama(field.kode).includes(opt)"
                                                           class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                                    <span x-text="opt"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Unggah Berkas -->
                                    <template x-if="field.tipe === 'file'">
                                        <input type="file" :id="'tambahan_' + field.kode" :name="'tambahan[' + field.kode + ']'"
                                               accept="image/png,image/jpeg,application/pdf"
                                               class="block w-full text-xs sm:text-sm text-slate-500 file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 bg-white border border-slate-200 rounded-xl p-1.5 transition">
                                    </template>

                                    <p x-show="field.help" x-text="field.help" class="text-[11px] sm:text-xs text-slate-500 mt-1"></p>
                                    <p x-show="field.tipe === 'file'" class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, atau PDF (maks. 2 MB).</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- Tombol Submit (Responsive Stack on Mobile) -->
                <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 sm:gap-3">
                    <a href="{{ route('client.dashboard') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs sm:text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition duration-150 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Permohonan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function formulirPermohonan(fieldMap, syaratMap, pendudukDefaults, oldTambahan, jenisSuratId) {
        return {
            fieldMap: fieldMap || {},
            syaratMap: syaratMap || {},
            pendudukDefaults: pendudukDefaults || {},
            oldTambahan: oldTambahan || {},
            jenisSuratId: jenisSuratId ? String(jenisSuratId) : '',

            get activeFields() {
                const fields = this.fieldMap[this.jenisSuratId];
                return Array.isArray(fields) ? fields : [];
            },

            get activeSyarat() {
                const syarat = this.syaratMap[this.jenisSuratId];
                return Array.isArray(syarat) ? syarat : [];
            },

            nilaiField(field) {
                const lama = this.oldTambahan ? this.oldTambahan[field.kode] : '';
                if (lama !== null && lama !== undefined && lama !== '' && typeof lama !== 'object') {
                    return lama;
                }
                if (field.default_from && this.pendudukDefaults[field.default_from]) {
                    return this.pendudukDefaults[field.default_from];
                }
                return '';
            },

            centangLama(kode) {
                const nilai = this.oldTambahan ? this.oldTambahan[kode] : null;
                if (Array.isArray(nilai)) {
                    return nilai;
                }
                return nilai ? [nilai] : [];
            },
        }
    }
    </script>
</x-app-layout>
