<x-guest-layout>
    <div x-data="{ showProses: {{ session('status') ? 'true' : 'false' }} }">
        <div class="mb-5 text-center sm:text-left">
            <h2 class="text-xl font-extrabold text-slate-800">Lupa Kata Sandi</h2>
            <p class="text-xs text-slate-500 mt-1">
                Masukkan NIK dan kata sandi baru yang Anda inginkan. Pengajuan akan diverifikasi petugas desa.
            </p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Info Alur -->
        <div class="mb-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 leading-relaxed">
            <span class="font-bold text-slate-700">Bagaimana caranya?</span>
            Isi NIK dan kata sandi baru yang Anda inginkan, lalu ajukan. Petugas desa akan menyetujui, dan kata sandi akun Anda akan menjadi seperti yang Anda ajukan.
        </div>

        <form method="POST" action="{{ route('password.request.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">
                    Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span>
                </label>
                <input id="nik"
                       type="text"
                       name="nik"
                       value="{{ old('nik') }}"
                       maxlength="16"
                       inputmode="numeric"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="3529xxxxxxxxxxxx"
                       class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono tracking-wider py-2.5 px-3.5 transition">
                @error('nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                    Kata Sandi Baru yang Diinginkan <span class="text-red-500">*</span>
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       minlength="6"
                       autocomplete="new-password"
                       placeholder="Minimal 6 karakter"
                       class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3.5 transition">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                    Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ketik ulang kata sandi baru"
                       class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3.5 transition">
            </div>

            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span>Ajukan Reset Kata Sandi</span>
            </button>
        </form>

        <div class="text-center pt-4 mt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Sudah ingat kata sandi Anda?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:underline">Kembali ke Halaman Masuk</a>
            </p>
            <p class="text-[11px] text-slate-400 mt-2 leading-relaxed">
                NIK belum terdaftar atau akun belum dibuat? Silakan melapor ke Balai Desa Saobi.
            </p>
        </div>

        <!-- ================= POPUP: PERMINTAAN SEDANG DIPROSES ================= -->
        <div x-show="showProses"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-700"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
             style="display: none;">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-sm w-full p-6 sm:p-7 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-extrabold text-slate-800">Permintaan Anda Sedang Diproses</h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                        {{ session('status') ?? 'Permintaan Anda sedang diproses. Mohon tunggu petugas desa menyetujui pengajuan kata sandi Anda.' }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 pt-1">
                    <button type="button" @click="showProses = false"
                            class="w-full py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                        Mengerti
                    </button>
                    <a href="{{ route('login') }}"
                       class="w-full text-center py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition">
                        Ke Halaman Masuk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
