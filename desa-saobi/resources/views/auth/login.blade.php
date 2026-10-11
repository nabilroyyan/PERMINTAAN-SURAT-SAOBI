<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-xl font-bold text-slate-800">Masuk ke Akun Anda</h2>
        <p class="text-xs text-slate-500 mt-1">Gunakan akun terdaftar Anda untuk mengakses layanan surat online Desa Saobi</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-bold text-xs text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Kata Sandi (Password)')" class="font-bold text-xs text-slate-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-xs text-slate-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full py-2.5">
                Masuk Sekarang
            </x-primary-button>
        </div>

        <div class="text-center pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Belum memiliki akun warga?
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
