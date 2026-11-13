<!-- Modal Pratinjau Surat (tampil di layar sebelum diunduh) -->
<div x-data="suratPreviewModal()"
     x-show="open"
     @keydown.escape.window="close()"
     class="fixed inset-0 z-[80] flex items-center justify-center p-2 sm:p-6"
     style="display: none;">
    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" @click="close()"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[94vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-slate-200">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-sm font-bold text-slate-800">Pratinjau Surat</h3>
            </div>
            <button type="button" @click="close()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 bg-slate-100 overflow-hidden">
            <template x-if="url">
                <iframe :src="url" class="w-full" style="height: 76vh; border: 0;" title="Pratinjau Surat"></iframe>
            </template>
        </div>

        <div class="flex items-center justify-end gap-3 px-4 sm:px-5 py-3 border-t border-slate-200">
            <button type="button" @click="close()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs sm:text-sm rounded-xl transition">
                Tutup
            </button>
            <a x-show="downloadUrl" :href="downloadUrl" x-cloak
               class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Unduh PDF
            </a>
        </div>
    </div>
</div>

<script>
function suratPreviewModal() {
    return {
        open: false,
        url: '',
        downloadUrl: '',

        init() {
            window.addEventListener('buka-surat', (event) => {
                const detail = event.detail || {};
                this.url = detail.url || '';
                this.downloadUrl = detail.download || '';
                this.open = true;
            });
        },

        close() {
            this.open = false;
            this.url = '';
            this.downloadUrl = '';
        },
    };
}

window.openSuratPreview = function (url, download) {
    window.dispatchEvent(new CustomEvent('buka-surat', {
        detail: { url: url, download: download || '' },
    }));
};
</script>
