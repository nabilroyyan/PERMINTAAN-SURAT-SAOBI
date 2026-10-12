<!-- Top Progress Bar (Nano / NProgress Style) -->
<div id="global-top-progress-bar"
     class="fixed top-0 left-0 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-400 to-emerald-300 z-[99999] transition-all duration-200 pointer-events-none opacity-0 shadow-sm shadow-emerald-500/50"
     style="width: 0%;">
</div>

<!-- Global Loading Overlay -->
<div id="global-loading-overlay"
     class="fixed inset-0 z-[99998] flex items-center justify-center bg-slate-950/40 backdrop-blur-xs transition-opacity duration-300 opacity-0 pointer-events-none"
     aria-live="polite">
    <div class="loading-card bg-white/95 backdrop-blur-md rounded-3xl p-6 sm:p-7 shadow-2xl border border-slate-100 max-w-xs sm:max-w-sm w-full mx-4 flex flex-col items-center text-center transform transition-transform duration-300 scale-95">
        <!-- Logo & Spinner Animation -->
        <div class="relative w-16 h-16 flex items-center justify-center mb-4">
            <!-- Rotating Spinner Ring -->
            <div class="absolute inset-0 rounded-full border-[3px] border-emerald-100 border-t-emerald-600 animate-spin"></div>
            <!-- Center Glowing Emblem -->
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-md shadow-emerald-600/30 animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Dynamic Context Title -->
        <h4 id="global-loading-title" class="text-base font-bold text-slate-800 tracking-tight">
            Memuat Halaman...
        </h4>

        <!-- Dynamic Context Subtitle -->
        <p id="global-loading-subtitle" class="text-xs text-slate-500 mt-1">
            Mohon tunggu sebentar...
        </p>

        <!-- Indeterminate Progress Indicator -->
        <div class="w-36 h-1.5 bg-slate-100 rounded-full mt-4 overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full animate-indeterminate"></div>
        </div>
    </div>
</div>
