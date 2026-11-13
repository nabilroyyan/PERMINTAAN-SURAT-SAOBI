@php
    $template = $template ?? null;
    $contentValue = old('konten', $template ? ($template->konten ?? '') : ($defaultKonten ?? ''));
    $namaValue = old('nama', $template?->nama ?? '');
@endphp

<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8"
     x-data="templateEditor(@js([
        'konten' => $contentValue,
        'judul' => $namaValue,
        'variabel' => $template?->variabel_tambahan ?? [],
        'previewUrl' => route('admin.template-surat.preview'),
        'csrf' => csrf_token(),
     ]))">

    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if (!empty($method) && strtoupper($method) !== 'POST')
            @method($method)
        @endif

        <input type="hidden" name="variabel_tambahan_json" :value="JSON.stringify(variabel)">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Editor Template -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/50 via-teal-50/20 to-white">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800">Informasi Template</h3>
                        <p class="text-xs text-slate-500 mt-1">Beri nama template agar mudah dikenali saat dipilih pada jenis surat.</p>
                    </div>

                    <div class="p-4 sm:p-6 space-y-4">
                        <div>
                            <label for="nama" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Nama Template <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   x-model="judul"
                                   value="{{ $namaValue }}"
                                   placeholder="Contoh: Surat Keterangan Domisili"
                                   class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3"
                                   required>
                            @error('nama')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase mb-1.5">
                                Deskripsi (Opsional)
                            </label>
                            <textarea name="deskripsi"
                                      id="deskripsi"
                                      rows="2"
                                      placeholder="Keterangan singkat mengenai peruntukan template ini"
                                      class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2 px-3">{{ old('deskripsi', $template?->deskripsi ?? '') }}</textarea>
                        </div>

                        <div class="flex items-start gap-2.5 pt-1">
                            <input type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', $template?->is_active ?? true) ? 'checked' : '' }}
                                   class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 mt-0.5">
                            <label for="is_active" class="text-xs font-semibold text-slate-700">
                                Aktifkan template ini sehingga dapat dipilih pada jenis surat
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Editor Isi Surat -->
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Isi / Badan Surat</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Tulis isi surat di bawah. Klik tombol variabel pada panel kanan untuk menyisipkan data otomatis.
                            </p>
                        </div>
                        <button type="button"
                                @click="openPreview()"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Pratinjau Surat
                        </button>
                    </div>

                    <div class="p-4 sm:p-6">
                        <textarea name="konten"
                                  id="konten"
                                  x-ref="kontenField"
                                  x-model="konten"
                                  rows="18"
                                  spellcheck="false"
                                  placeholder="Ketik isi surat di sini..."
                                  class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3 font-mono leading-relaxed"></textarea>
                        @error('konten')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-500 mt-2">
                            Anda dapat menuliskan tag HTML sederhana seperti
                            <code class="font-mono text-emerald-700">&lt;strong&gt;</code>,
                            <code class="font-mono text-emerald-700">&lt;u&gt;</code>, atau
                            <code class="font-mono text-emerald-700">&lt;p&gt;</code> bila perlu.
                        </p>
                    </div>
                </div>

                <!-- Variabel Tambahan (Diisi Pemohon) -->
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Variabel Tambahan (Diisi Pemohon)</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                Isian yang harus diisi warga saat mengajukan surat ini, misalnya batas tanah sebelah barat/timur/utara/selatan.
                            </p>
                        </div>
                        <button type="button" @click="tambahVariabel()"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Variabel
                        </button>
                    </div>

                    <div class="p-4 sm:p-6 space-y-3">
                        <template x-if="variabel.length === 0">
                            <p class="text-xs text-slate-400 italic">
                                Belum ada variabel tambahan. Klik "Tambah Variabel" bila surat ini butuh isian khusus dari pemohon.
                            </p>
                        </template>

                        <template x-for="(item, index) in variabel" :key="index">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end bg-slate-50/80 border border-slate-200/80 rounded-xl p-3">
                                <div class="sm:col-span-4">
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Label Isian</label>
                                    <input type="text" x-model="item.label" placeholder="Mis: Batas Tanah Sebelah Barat"
                                           class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kode Variabel</label>
                                    <input type="text" x-model="item.kode" placeholder="otomatis dari label"
                                           class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5 font-mono">
                                    <p class="text-[10px] text-emerald-700 mt-1 font-mono" x-text="'{' + kodeVariabel(item) + '}'"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tipe</label>
                                    <select x-model="item.tipe"
                                            class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                                        <option value="text">Teks</option>
                                        <option value="textarea">Teks Panjang</option>
                                        <option value="date">Tanggal</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2 flex items-center gap-2 pb-2">
                                    <input type="checkbox" x-model="item.wajib" :id="'wajib_' + index"
                                           class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <label :for="'wajib_' + index" class="text-xs font-semibold text-slate-700">Wajib</label>
                                </div>
                                <div class="sm:col-span-1 flex sm:justify-end">
                                    <button type="button" @click="hapusVariabel(index)"
                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Kolom Panel Variabel -->
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-emerald-950 to-slate-900 text-white rounded-2xl sm:rounded-3xl border border-emerald-800 shadow-sm p-5 lg:sticky lg:top-24">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <h3 class="text-sm font-bold uppercase tracking-wider">Variabel Tersedia</h3>
                    </div>
                    <p class="text-[11px] text-emerald-200/80 mb-4">
                        Klik variabel untuk menyisipkan ke posisi kursor pada isi surat.
                    </p>

                    <div class="space-y-4 max-h-[520px] overflow-y-auto pr-1">
                        @foreach ($variableGroups as $groupName => $group)
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/80 mb-1.5">
                                    {{ $groupName }}
                                </p>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($group as $tag => $desc)
                                        <button type="button"
                                                @click="insertTag('{{ $tag }}')"
                                                title="{{ $desc }}"
                                                class="px-2 py-1 bg-white/10 hover:bg-emerald-500/30 text-emerald-100 border border-white/10 hover:border-emerald-400/50 rounded-md font-mono text-[11px] font-semibold transition active:scale-95">
                                            {{ '{' . $tag . '}' }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div x-show="variabel.length > 0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300/80 mb-1.5">
                                Variabel Tambahan
                            </p>
                            <div class="flex flex-wrap gap-1.5">
                                <template x-for="(item, index) in variabel" :key="'v' + index">
                                    <button type="button" @click="insertTag(kodeVariabel(item))"
                                            class="px-2 py-1 bg-emerald-500/20 hover:bg-emerald-500/40 text-emerald-50 border border-emerald-400/30 rounded-md font-mono text-[11px] font-semibold transition active:scale-95"
                                            x-text="'{' + kodeVariabel(item) + '}'"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pb-8">
            <a href="{{ route('admin.template-surat.index') }}"
               class="w-full sm:w-auto text-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition">
                Batal
            </a>
            <button type="submit"
                    class="w-full sm:w-auto justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm shadow-emerald-600/20 transition">
                {{ $submitLabel ?? 'Simpan Template' }}
            </button>
        </div>
    </form>

    <!-- Modal Pratinjau -->
    <div x-show="previewOpen"
         x-transition.opacity
         class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-6"
         style="display: none;">
        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" @click="previewOpen = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[92vh] flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-slate-800">Pratinjau Surat (Data Contoh)</h3>
                </div>
                <button type="button" @click="previewOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-auto bg-slate-100 p-3">
                <div x-show="previewLoading" class="flex items-center justify-center py-16 text-slate-500 text-sm">
                    Memuat pratinjau...
                </div>
                <p x-show="previewError" x-text="previewError" class="text-center text-red-600 text-sm py-16"></p>
                <iframe x-show="!previewLoading && !previewError"
                        :srcdoc="previewHtml"
                        class="w-full bg-white shadow-sm"
                        style="height: 78vh; border: 0;"
                        title="Pratinjau Surat"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function templateEditor(config) {
    return {
        konten: config.konten || '',
        judul: config.judul || '',
        variabel: Array.isArray(config.variabel) ? config.variabel : [],
        previewUrl: config.previewUrl,
        csrf: config.csrf,
        previewOpen: false,
        previewLoading: false,
        previewError: '',
        previewHtml: '',

        tambahVariabel() {
            this.variabel.push({ kode: '', label: '', tipe: 'text', wajib: false });
        },

        hapusVariabel(index) {
            this.variabel.splice(index, 1);
        },

        kodeVariabel(item) {
            const sumber = (item.kode && item.kode.trim()) || item.label || '';
            const kode = sumber
                .toString()
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_+|_+$/g, '');
            return kode || 'variabel';
        },

        insertTag(tag) {
            const el = this.$refs.kontenField;
            const token = '{' + tag + '}';
            if (!el) {
                this.konten += token;
                return;
            }
            const start = el.selectionStart ?? this.konten.length;
            const end = el.selectionEnd ?? this.konten.length;
            this.konten = this.konten.substring(0, start) + token + this.konten.substring(end);
            this.$nextTick(() => {
                el.focus();
                el.setSelectionRange(start + token.length, start + token.length);
            });
        },

        async openPreview() {
            this.previewOpen = true;
            this.previewLoading = true;
            this.previewError = '';
            this.previewHtml = '';

            try {
                const res = await fetch(this.previewUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'text/html',
                    },
                    body: JSON.stringify({
                        konten: this.konten,
                        judul: this.judul,
                        variabel_tambahan_json: JSON.stringify(this.variabel),
                    }),
                });

                if (!res.ok) {
                    throw new Error('Gagal memuat pratinjau (' + res.status + ').');
                }

                this.previewHtml = await res.text();
            } catch (e) {
                this.previewError = e.message || 'Gagal memuat pratinjau.';
            } finally {
                this.previewLoading = false;
            }
        },
    }
}
</script>
