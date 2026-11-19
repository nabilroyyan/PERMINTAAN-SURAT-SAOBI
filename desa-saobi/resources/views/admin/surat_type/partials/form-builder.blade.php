@php
    $initialFields = null;
    if (old('form_fields_json')) {
        $decoded = json_decode(old('form_fields_json'), true);
        $initialFields = is_array($decoded) ? $decoded : null;
    }
    if ($initialFields === null) {
        $initialFields = $fields ?? [];
    }
@endphp

<div x-data="formFieldBuilder(@js($initialFields))"
     class="p-4 sm:p-5 bg-emerald-50/60 rounded-2xl border border-emerald-200/80 space-y-4">

    <input type="hidden" name="form_fields_json" :value="serialized">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-emerald-200/60 pb-3">
        <div>
            <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Formulir Isian Warga (Dinamis)
            </h4>
            <p class="text-[11px] text-slate-500 mt-1">
                Tentukan isian yang harus diisi warga saat mengajukan surat ini. Data akan tersedia sebagai variabel di template surat.
            </p>
        </div>
        <button type="button" @click="tambah()"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Isian
        </button>
    </div>

    <template x-if="fields.length === 0">
        <p class="text-xs text-slate-400 italic">
            Belum ada isian tambahan. Klik "Tambah Isian" bila jenis surat ini butuh data khusus dari pemohon.
        </p>
    </template>

    <div class="space-y-3">
        <template x-for="(item, index) in fields" :key="index">
            <div class="bg-white border border-slate-200/80 rounded-xl p-3 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-end">
                    <div class="sm:col-span-4">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Label Isian</label>
                        <input type="text" x-model="item.label" placeholder="Mis: Nama Ayah / Batas Tanah Barat"
                               class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kode Variabel</label>
                        <input type="text" x-model="item.kode" placeholder="otomatis dari label"
                               class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5 font-mono">
                        <p class="text-[10px] text-emerald-700 mt-1 font-mono" x-text="'{' + kodeField(item) + '}'"></p>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tipe Isian</label>
                        <select x-model="item.tipe"
                                class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                            @foreach (\App\Enums\FormFieldType::cases() as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2 flex items-center gap-2 pb-2">
                        <input type="checkbox" x-model="item.wajib" :id="'wajib_' + index"
                               class="rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                        <label :for="'wajib_' + index" class="text-xs font-semibold text-slate-700">Wajib</label>
                    </div>
                </div>

                <template x-if="needsOptions(item.tipe)">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Daftar Pilihan (satu per baris)</label>
                        <textarea x-model="item.optionsText" rows="3" placeholder="Pilihan 1&#10;Pilihan 2&#10;Pilihan 3"
                                  class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5 font-mono"></textarea>
                    </div>
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Placeholder (opsional)</label>
                        <input type="text" x-model="item.placeholder"
                               class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Keterangan Bantuan (opsional)</label>
                        <input type="text" x-model="item.help"
                               class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                    </div>
                </div>

                <template x-if="item.tipe !== 'file'">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Isi Otomatis dari Data Penduduk</label>
                        <select x-model="item.default_from"
                                class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-2.5">
                            <option value="">— Tidak ada (diisi manual warga) —</option>
                            @foreach (\App\Support\FormFieldSchema::DEFAULT_SOURCES as $sourceKode => $sourceLabel)
                                <option value="{{ $sourceKode }}">{{ $sourceLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-slate-100">
                    <button type="button" @click="naik(index)" :disabled="index === 0"
                            class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition disabled:opacity-30" title="Naikkan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </button>
                    <button type="button" @click="turun(index)" :disabled="index === fields.length - 1"
                            class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition disabled:opacity-30" title="Turunkan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <button type="button" @click="hapus(index)"
                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function formFieldBuilder(initial) {
    const optionTypes = ['select', 'radio', 'checkbox'];

    const mapInitial = (list) => (Array.isArray(list) ? list : []).map((f) => ({
        kode: f.kode || '',
        label: f.label || '',
        tipe: f.tipe || 'text',
        wajib: !!f.wajib,
        placeholder: f.placeholder || '',
        help: f.help || '',
        default_from: f.default_from || '',
        optionsText: Array.isArray(f.options) ? f.options.join('\n') : (f.options || ''),
    }));

    return {
        fields: mapInitial(initial),

        tambah() {
            this.fields.push({ kode: '', label: '', tipe: 'text', wajib: false, placeholder: '', help: '', default_from: '', optionsText: '' });
        },

        hapus(index) {
            this.fields.splice(index, 1);
        },

        naik(index) {
            if (index > 0) {
                const a = this.fields;
                [a[index - 1], a[index]] = [a[index], a[index - 1]];
            }
        },

        turun(index) {
            if (index < this.fields.length - 1) {
                const a = this.fields;
                [a[index + 1], a[index]] = [a[index], a[index + 1]];
            }
        },

        needsOptions(tipe) {
            return optionTypes.includes(tipe);
        },

        kodeField(item) {
            const sumber = (item.kode && item.kode.trim()) || item.label || '';
            const kode = sumber
                .toString()
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '_')
                .replace(/^_+|_+$/g, '');
            return kode || 'variabel';
        },

        get serialized() {
            return JSON.stringify(this.fields.map((f) => ({
                kode: f.kode,
                label: f.label,
                tipe: f.tipe,
                wajib: f.wajib,
                placeholder: f.placeholder,
                help: f.help,
                default_from: f.default_from || null,
                options: this.needsOptions(f.tipe)
                    ? f.optionsText.split(/\r?\n/).map((s) => s.trim()).filter(Boolean)
                    : [],
            })));
        },
    }
}
</script>
