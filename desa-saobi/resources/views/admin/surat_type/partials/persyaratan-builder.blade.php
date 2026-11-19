@php
    $rawInitial = old('persyaratan_json');
    if ($rawInitial) {
        $decoded = json_decode($rawInitial, true);
        $rawInitial = is_array($decoded) ? $decoded : [];
    } else {
        $rawInitial = $persyaratan ?? [];
    }

    $initialItems = [];
    foreach ((array) $rawInitial as $item) {
        if (is_array($item)) {
            $initialItems[] = [
                'nama' => trim((string) ($item['nama'] ?? $item['label'] ?? '')),
                'wajib' => (bool) ($item['wajib'] ?? false),
            ];
        } elseif (is_string($item) && trim($item) !== '') {
            $initialItems[] = ['nama' => trim($item), 'wajib' => false];
        }
    }
@endphp

<div x-data="persyaratanBuilder(@js($initialItems))"
     class="p-4 sm:p-5 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-3">

    <input type="hidden" name="persyaratan_json" :value="json" x-effect="$el.value = json">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-amber-200/60 pb-3">
        <div>
            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                Persyaratan Surat (Dinamis)
            </h4>
            <p class="text-[11px] text-slate-500 mt-1">
                Tambahkan persyaratan yang harus dibawa warga untuk jenis surat ini, dan tentukan apakah <strong>Wajib</strong> atau <strong>Opsional</strong>. Isian surat itu sendiri diatur pada Template Isi Surat.
            </p>
        </div>
        <button type="button" @click="tambah()"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-sm transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Persyaratan
        </button>
    </div>

    <template x-if="items.length === 0">
        <p class="text-xs text-slate-400 italic">
            Belum ada persyaratan. Klik "Tambah Persyaratan" untuk menambahkan syarat.
        </p>
    </template>

    <div class="space-y-2">
        <template x-for="(item, index) in items" :key="index">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <span class="hidden sm:block shrink-0 w-5 text-right text-[11px] font-mono text-slate-400" x-text="(index + 1) + '.'"></span>
                <input type="text" x-model="item.nama"
                       placeholder="Contoh: Fotokopi KTP / Surat Pengantar RT"
                       class="flex-1 min-w-0 rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500 text-xs sm:text-sm py-2 px-2.5">
                <select x-model="item.wajib"
                        class="shrink-0 rounded-lg border-slate-300 focus:border-amber-500 focus:ring-amber-500 text-xs py-2 px-2.5 font-semibold">
                    <option value="tidak">Opsional</option>
                    <option value="ya">Wajib</option>
                </select>
                <button type="button" @click="hapus(index)"
                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition shrink-0" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>
</div>

<script>
function persyaratanBuilder(initial) {
    return {
        items: (Array.isArray(initial) ? initial : []).map((i) => ({
            nama: i.nama || '',
            wajib: i.wajib ? 'ya' : 'tidak',
        })),

        tambah() {
            this.items.push({ nama: '', wajib: 'tidak' });
        },

        hapus(index) {
            this.items.splice(index, 1);
        },

        get json() {
            return JSON.stringify(
                this.items.map((i) => ({
                    nama: (i.nama || '').trim(),
                    wajib: i.wajib === 'ya',
                }))
            );
        },
    };
}
</script>
