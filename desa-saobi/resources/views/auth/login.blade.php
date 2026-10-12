<x-guest-layout>
    <div x-data="{
        tab: '{{ $errors->has('email') ? 'petugas' : 'warga' }}',
        nik: '{{ old('nik', '') }}',
        step: {{ old('nik') && !$errors->has('nik') ? 2 : 1 }},
        checking: false,
        verifiedName: '',
        verifiedDusun: '',
        nikError: '{{ $errors->first('nik') }}',

        checkNik() {
            const cleanNik = (this.nik || '').trim();
            if (!cleanNik || cleanNik.length !== 16 || !/^\d+$/.test(cleanNik)) {
                this.nikError = 'Nomor NIK harus 16 digit angka.';
                return;
            }

            this.checking = true;
            this.nikError = '';

            fetch('{{ route('login.check-nik') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nik: cleanNik })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                this.checking = false;
                if (res.status === 200 && res.body.success) {
                    if (!res.body.has_account) {
                        this.nikError = res.body.message;
                        return;
                    }
                    this.verifiedName = res.body.nama;
                    this.verifiedDusun = res.body.dusun;
                    this.step = 2;
                    this.$nextTick(() => {
                        const pwd = document.getElementById('warga_password');
                        if (pwd) pwd.focus();
                    });
                } else {
                    this.nikError = res.body.message || 'NIK tidak terdaftar dalam data penduduk Desa Saobi.';
                }
            })
            .catch(() => {
                this.checking = false;
                this.nikError = 'Gagal terhubung ke server. Periksa koneksi Anda.';
            });
        },

        resetToStep1() {
            this.step = 1;
            this.nikError = '';
            this.verifiedName = '';
            this.$nextTick(() => {
                const nikInput = document.getElementById('warga_nik');
                if (nikInput) nikInput.focus();
            });
        }
    }">
        <!-- Header & Nav Tabs -->
        <div class="mb-5 text-center sm:text-left">
            <h2 class="text-xl font-extrabold text-slate-800">Masuk Layanan Surat</h2>
            <p class="text-xs text-slate-500 mt-1">Sistem Pelayanan Administrasi Mandiri Penduduk Desa Saobi</p>

            <!-- Tab Switcher -->
            <div class="flex items-center p-1 bg-slate-100 rounded-xl mt-4 border border-slate-200/80">
                <button type="button"
                        @click="tab = 'warga'"
                        :class="tab === 'warga' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-2 px-3 text-xs rounded-lg transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Warga Desa (NIK)
                </button>
                <button type="button"
                        @click="tab = 'petugas'"
                        :class="tab === 'petugas' ? 'bg-white text-emerald-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-2 px-3 text-xs rounded-lg transition flex items-center justify-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Petugas / Admin
                </button>
            </div>
        </div>

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- ================= TAB 1: LOGIN WARGA (ALUR 2 LANGKAH CEK NIK) ================= -->
        <div x-show="tab === 'warga'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Form Login Warga -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- LANGKAH 1: INPUT & CEK NIK -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="warga_nik" class="block font-bold text-xs text-slate-700">
                            Nomor Induk Kependudukan (NIK)
                        </label>
                        <span class="text-[10px] text-emerald-700 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                            Wajib Penduduk Saobi
                        </span>
                    </div>

                    <div class="relative">
                        <input id="warga_nik"
                               type="text"
                               name="nik"
                               x-model="nik"
                               :readonly="step === 2"
                               maxlength="16"
                               inputmode="numeric"
                               placeholder="Contoh: 3529012345670001"
                               @keydown.enter.prevent="if (step === 1) checkNik()"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-mono tracking-wider py-2.5 px-3.5 transition"
                               :class="step === 2 ? 'bg-slate-50 text-slate-600 border-emerald-300' : 'bg-white'">

                        <div class="absolute right-2.5 top-2.5" x-show="checking">
                            <svg class="animate-spin h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Pesan Error NIK Tidak Sesuai -->
                    <template x-if="nikError">
                        <div class="mt-2.5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-start gap-2.5 shadow-2xs">
                            <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-xs font-medium" x-text="nikError"></div>
                        </div>
                    </template>
                </div>

                <!-- Tombol Cek NIK (Muncul pada Langkah 1) -->
                <div x-show="step === 1" class="pt-1">
                    <button type="button"
                            @click="checkNik()"
                            :disabled="checking"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition disabled:opacity-50">
                        <span x-show="!checking">Periksa & Verifikasi NIK</span>
                        <span x-show="checking">Memeriksa Database Desa Saobi...</span>
                        <svg x-show="!checking" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                    <p class="text-[11px] text-slate-400 text-center mt-2">
                        Pastikan NIK Anda terdaftar dalam buku induk kependudukan Desa Saobi.
                    </p>
                </div>

                <!-- LANGKAH 2: NIK TERVERIFIKASI -> INPUT KATA SANDI -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-98" x-transition:enter-end="opacity-100 scale-100" class="space-y-4 pt-1">
                    <!-- Kartu Identitas Terverifikasi -->
                    <div class="p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Penduduk Terverifikasi</p>
                                <p class="text-xs font-bold text-slate-800 truncate" x-text="verifiedName"></p>
                                <p class="text-[10px] text-slate-500" x-text="verifiedDusun"></p>
                            </div>
                        </div>
                        <button type="button"
                                @click="resetToStep1()"
                                class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 underline shrink-0">
                            Ganti NIK
                        </button>
                    </div>

                    <!-- Input Kata Sandi Warga -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="warga_password" class="block font-bold text-xs text-slate-700">
                                Kata Sandi Akun
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-[11px] text-emerald-700 hover:text-emerald-800 font-semibold" href="{{ route('password.request') }}">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <input id="warga_password"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="Masukkan kata sandi akun Anda"
                               class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3.5">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_warga" class="inline-flex items-center cursor-pointer">
                            <input id="remember_warga" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                            <span class="ms-2 text-xs text-slate-600 font-medium">Ingat Saya</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk -->
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/25 transition">
                        <span>Masuk ke Layanan Surat</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- ================= TAB 2: LOGIN PETUGAS / ADMIN / KADES ================= -->
        <div x-show="tab === 'petugas'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Petugas -->
                <div>
                    <label for="petugas_email" class="block font-bold text-xs text-slate-700 mb-1">
                        Email Petugas / Administrator
                    </label>
                    <input id="petugas_email"
                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3.5"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="admin@saobi.desa.id"
                           autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password Petugas -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="petugas_password" class="block font-bold text-xs text-slate-700">
                            Kata Sandi (Password)
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] text-emerald-700 hover:text-emerald-800 font-semibold" href="{{ route('password.request') }}">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <input id="petugas_password"
                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3.5"
                           type="password"
                           name="password"
                           placeholder="••••••••"
                           autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_petugas" class="inline-flex items-center cursor-pointer">
                        <input id="remember_petugas" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                        <span class="ms-2 text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <!-- Tombol Masuk Petugas -->
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white font-bold text-xs rounded-xl shadow-md transition">
                    <span>Masuk ke Panel Administrasi</span>
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer Info: Pendaftaran Mandiri Dihapus -->
        <div class="text-center pt-4 mt-2 border-t border-slate-100">
            <p class="text-xs text-slate-400 leading-relaxed">
                Akun warga dibuat langsung berdasarkan <strong>Data Kependudukan Desa Saobi</strong>. Jika NIK Anda belum terdaftar, silakan melapor ke Balai Desa Saobi.
            </p>
        </div>
    </div>
</x-guest-layout>
