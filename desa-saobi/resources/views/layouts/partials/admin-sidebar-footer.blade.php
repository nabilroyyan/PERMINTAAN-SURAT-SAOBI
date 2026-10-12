<div class="p-3 border-t border-slate-800 bg-slate-950/60 mt-auto">
    <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-800/40 border border-slate-800">
        <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold text-white truncate">
                {{ Auth::user()->name }}
            </p>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span class="text-[10px] text-emerald-400 font-medium">{{ Auth::user()->role_label }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    title="Keluar (Logout)"
                    class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</div>
