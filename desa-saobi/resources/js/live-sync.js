let liveSyncInterval = null;
let isRefreshing = false;

function playSoftChime() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        const now = ctx.currentTime;

        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(587.33, now); // D5
        osc.frequency.exponentialRampToValueAtTime(880, now + 0.12); // A5

        gain.gain.setValueAtTime(0.08, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.45);

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.start(now);
        osc.stop(now + 0.45);
    } catch (e) {
        // audio might be blocked if user has not interacted yet
    }
}

export function showLiveUpdateToast(message, title = 'Pembaruan Real-Time!') {
    playSoftChime();

    const container = document.getElementById('saobi-live-toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto transform translate-y-2 opacity-0 transition-all duration-300 bg-white/95 backdrop-blur-md rounded-2xl p-4 shadow-xl border border-emerald-200/90 flex items-start gap-3.5';

    toast.innerHTML = `
        <div class="relative shrink-0 mt-0.5">
            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
            </span>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <h5 class="text-xs font-bold text-slate-900 flex items-center gap-2">
                <span>${title}</span>
                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-semibold">Terkini</span>
            </h5>
            <p class="text-xs text-slate-600 mt-1 leading-relaxed font-medium">
                ${message}
            </p>
            <p class="text-[10px] text-emerald-600 font-semibold mt-1.5 flex items-center gap-1.5">
                <svg class="w-3 h-3 animate-spin text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Memperbarui tampilan secara otomatis...
            </p>
        </div>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-2', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    });
}

export function initLiveSync() {
    const syncEl = document.getElementById('saobi-live-sync');
    if (!syncEl) return;

    const url = syncEl.dataset.url;
    if (!url) return;

    const role = syncEl.dataset.role;
    const context = syncEl.dataset.context;

    async function checkUpdate() {
        if (isRefreshing || document.hidden) return;

        try {
            const params = new URLSearchParams();
            params.set('context', context);

            if (context === 'show') {
                params.set('id', syncEl.dataset.id || '');
                params.set('status', syncEl.dataset.status || '');
                params.set('updated', syncEl.dataset.updated || '0');
                params.set('has_file', syncEl.dataset.hasFile || 'false');
            } else if (context === 'admin-monitor') {
                params.set('max_created', syncEl.dataset.maxCreated || '0');
                params.set('total', syncEl.dataset.total || '0');
            } else {
                params.set('max_updated', syncEl.dataset.maxUpdated || '0');
                params.set('total', syncEl.dataset.total || '0');
            }

            const response = await fetch(`${url}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            if (data && data.changed) {
                isRefreshing = true;
                clearInterval(liveSyncInterval);

                // Tampilkan notifikasi toast
                showLiveUpdateToast(data.message || 'Ada pembaruan data dari Admin!');

                // Tunggu 1.3 detik agar user sempat membaca notifikasi, lalu muat ulang secara mulus
                setTimeout(() => {
                    if (window.showLoading) {
                        window.showLoading('Memperbarui Halaman...', 'Menerapkan pembaruan dari Admin...');
                    }
                    window.location.reload();
                }, 1300);
            }
        } catch (err) {
            // Silently ignore network hiccup
        }
    }

    // Interval polling ringan setiap 3.5 detik ketika tab aktif
    clearInterval(liveSyncInterval);
    liveSyncInterval = setInterval(checkUpdate, 3500);

    // Langsung cek ketika tab kembali dibuka oleh pengguna
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden && !isRefreshing) {
            checkUpdate();
        }
    });

    window.checkLiveUpdate = checkUpdate;
}
