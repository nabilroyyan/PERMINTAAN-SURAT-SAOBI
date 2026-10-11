<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.jenis-surat.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                        ← Kembali ke Daftar Jenis Surat
                    </a>
                </div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-slate-800 tracking-tight mt-1">
                    Atur Format & Nomor: {{ $suratType->nama }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Kustomisasi struktur template nomor surat dan kelola nomor urut terakhir pada buku agenda desa.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8" x-data="suratFormatManager()">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-6 md:p-8 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-teal-50/20 to-white">
                <h3 class="text-base sm:text-lg font-bold text-slate-800">Form Pengaturan Format Nomor Surat</h3>
                <p class="text-xs text-slate-500 mt-1">Perubahan format ini akan langsung digunakan secara otomatis pada permohonan surat baru.</p>
            </div>

            <form action="{{ route('admin.jenis-surat.update', $suratType) }}" method="POST" class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama & Kode Surat -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase mb-1.5 sm:mb-2">
                            Nama Jenis Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nama"
                               id="nama"
                               x-model="nama"
                               value="{{ old('nama', $suratType->nama) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3"
                               required>
                        @error('nama')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode" class="block text-xs font-bold text-slate-700 uppercase mb-1.5 sm:mb-2">
                            Kode Singkatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="kode"
                               id="kode"
                               x-model="kode"
                               @input="updatePreview()"
                               value="{{ old('kode', $suratType->kode) }}"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono uppercase"
                               required>
                        @error('kode')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bagian Format Nomor & Counter -->
                <div class="p-4 sm:p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-4 sm:space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200/60 pb-3 gap-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Template Format Penomoran</h4>
                        </div>
                        <span class="text-[11px] text-slate-500">Gunakan tag variabel di bawah</span>
                    </div>

                    <div>
                        <label for="format_nomor" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Pola / Template Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="format_nomor"
                               id="format_nomor"
                               x-ref="formatInput"
                               x-model="formatNomor"
                               @input="updatePreview()"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 font-mono font-medium text-slate-800"
                               required>

                        <!-- Tombol Cepat Sisipkan Variabel -->
                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5 text-xs">
                            <span class="text-[11px] text-slate-400 font-medium me-1">Klik sisipkan:</span>
                            <button type="button" @click="insertTag('{NOMOR}')" class="px-2 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-slate-200 hover:border-emerald-300 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                + {NOMOR}
                            </button>
                            <button type="button" @click="insertTag('{KODE}')" class="px-2 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-slate-200 hover:border-emerald-300 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                + {KODE}
                            </button>
                            <button type="button" @click="insertTag('{TAHUN}')" class="px-2 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-slate-200 hover:border-emerald-300 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                + {TAHUN}
                            </button>
                            <button type="button" @click="insertTag('{BULAN}')" class="px-2 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-slate-200 hover:border-emerald-300 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                + {BULAN}
                            </button>
                            <button type="button" @click="insertTag('{BULAN_ROMAWI}')" class="px-2 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-slate-200 hover:border-emerald-300 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                + {BULAN_ROMAWI}
                            </button>
                        </div>
                    </div>

                    <!-- Pengaturan Counter & Digit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label for="nomor_terakhir" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Nomor Agenda Terakhir yang Digunakan <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="nomor_terakhir"
                                   id="nomor_terakhir"
                                   x-model="nomorTerakhir"
                                   @input="updatePreview()"
                                   min="0"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3 font-mono font-semibold"
                                   required>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Bila register desa sudah sampai no 15, ketik 15 (surat berikutnya otomatis no 16).
                            </p>
                        </div>

                        <div>
                            <label for="panjang_nomor" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Format Digit Nomor Urut <span class="text-red-500">*</span>
                            </label>
                            <select name="panjang_nomor"
                                    id="panjang_nomor"
                                    x-model="panjangNomor"
                                    @change="updatePreview()"
                                    class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3">
                                <option value="1">1 Digit (Contoh: 1, 2, 3...)</option>
                                <option value="2">2 Digit (Contoh: 01, 02, 03...)</option>
                                <option value="3">3 Digit (Contoh: 001, 002, 003...)</option>
                                <option value="4">4 Digit (Contoh: 0001, 0002...)</option>
                            </select>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Standar register surat desa adalah 2 digit (01, 02, dst).
                            </p>
                        </div>
                    </div>

                    <!-- Live Preview Kotak Hijau -->
                    <div class="p-3.5 sm:p-4 bg-emerald-950 text-white rounded-xl shadow-inner border border-emerald-800">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-emerald-300 font-semibold gap-1 mb-1">
                            <span>LIVE PREVIEW NOMOR SURAT BERIKUTNYA:</span>
                            <span class="text-[10px] bg-emerald-800/80 px-2 py-0.5 rounded text-emerald-200 w-fit">Akan otomatis terisi di form terbitkan surat</span>
                        </div>
                        <div class="text-base sm:text-lg font-mono font-bold text-emerald-200 tracking-wide break-all" x-text="preview">
                            {{ $suratType->generateNextNomorSurat() }}
                        </div>
                    </div>
                </div>

                <!-- Deskripsi & Persyaratan -->
                <div class="space-y-4">
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase mb-1.5 sm:mb-2">
                            Deskripsi / Penjelasan Surat
                        </label>
                        <textarea name="deskripsi"
                                  id="deskripsi"
                                  rows="2"
                                  class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3">{{ old('deskripsi', $suratType->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label for="persyaratan_text" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Persyaratan Dokumen (Satu per baris)
                        </label>
                        <textarea name="persyaratan_text"
                                  id="persyaratan_text"
                                  rows="3"
                                  placeholder="KTP&#10;KK&#10;Surat Pengantar RT"
                                  class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3 font-mono text-xs">{{ old('persyaratan_text', is_array($suratType->persyaratan) ? implode("\n", $suratType->persyaratan) : '') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Tuliskan tiap syarat di baris baru.</p>
                    </div>

                    <div class="flex items-start gap-2.5 pt-2">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', $suratType->is_active) ? 'checked' : '' }}
                               class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 mt-0.5">
                        <label for="is_active" class="text-xs font-semibold text-slate-700">
                            Aktifkan jenis surat ini (Warga dapat memilihnya di formulir permohonan surat online)
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit (Responsive Stack on Mobile) -->
                <div class="pt-4 sm:pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 sm:gap-3">
                    <a href="{{ route('admin.jenis-surat.index') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition flex items-center gap-2">
                        Simpan Pengaturan Nomor Surat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function suratFormatManager() {
        return {
            nama: @json(old('nama', $suratType->nama)),
            kode: @json(old('kode', $suratType->kode)),
            formatNomor: @json(old('format_nomor', $suratType->format_nomor ?: '470/{NOMOR}/' . $suratType->kode . '/435.312.108/{TAHUN}')),
            nomorTerakhir: {{ (int) old('nomor_terakhir', $suratType->nomor_terakhir ?? 0) }},
            panjangNomor: {{ (int) old('panjang_nomor', $suratType->panjang_nomor ?? 2) }},
            preview: '',

            init() {
                this.updatePreview();
            },

            insertTag(tag) {
                const input = this.$refs.formatInput;
                const start = input.selectionStart || this.formatNomor.length;
                const end = input.selectionEnd || this.formatNomor.length;
                this.formatNomor = this.formatNomor.substring(0, start) + tag + this.formatNomor.substring(end);
                this.updatePreview();
                this.$nextTick(() => {
                    input.focus();
                    input.setSelectionRange(start + tag.length, start + tag.length);
                });
            },

            updatePreview() {
                const nextNum = (parseInt(this.nomorTerakhir) || 0) + 1;
                const padded = String(nextNum).padStart(parseInt(this.panjangNomor) || 2, '0');
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                const romanMonth = romanMonths[now.getMonth()];

                let res = this.formatNomor || '';
                res = res.replace(/\{NOMOR\}/g, padded);
                res = res.replace(/\{KODE\}/g, (this.kode || 'DS').toUpperCase());
                res = res.replace(/\{TAHUN\}/g, year);
                res = res.replace(/\{BULAN\}/g, month);
                res = res.replace(/\{BULAN_ROMAWI\}/g, romanMonth);

                this.preview = res;
            }
        }
    }
    </script>
</x-app-layout>
