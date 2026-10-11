<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-xl font-bold text-slate-800">Registrasi Akun Warga Saobi</h2>
        <p class="text-xs text-slate-500 mt-1">
            Masukkan NIK dan Nomor KK Anda untuk memverifikasi data kependudukan secara otomatis.
        </p>
    </div>

    @if ($errors->any())
        <div class="p-3.5 mb-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <p class="font-bold mb-1">Periksa kembali data Anda:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm" class="space-y-4">
        @csrf

        <!-- Langkah 1: NIK & No KK -->
        <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/80 space-y-3">
            <span class="block text-[11px] font-bold text-emerald-800 uppercase tracking-wider">
                Langkah 1: Verifikasi Kependudukan
            </span>

            <!-- NIK -->
            <div>
                <label for="nik" class="block text-xs font-bold text-slate-700 mb-1">
                    Nomor Induk Kependudukan (NIK 16 Digit) <span class="text-red-500">*</span>
                </label>
                <input id="nik"
                       type="text"
                       name="nik"
                       value="{{ old('nik') }}"
                       maxlength="16"
                       required
                       placeholder="3529xxxxxxxxxxxx"
                       class="block w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm py-2 px-3">
                @error('nik')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No KK -->
            <div>
                <label for="no_kk" class="block text-xs font-bold text-slate-700 mb-1">
                    Nomor Kartu Keluarga (No. KK 16 Digit) <span class="text-red-500">*</span>
                </label>
                <input id="no_kk"
                       type="text"
                       name="no_kk"
                       value="{{ old('no_kk') }}"
                       maxlength="16"
                       required
                       placeholder="3529xxxxxxxxxxxx"
                       class="block w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-sm py-2 px-3">
                @error('no_kk')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Cek Data -->
            <button type="button"
                    id="btnCekNik"
                    class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span id="btnCekText">Verifikasi NIK & KK Saya</span>
            </button>
            <div id="statusVerifikasi" class="hidden text-xs p-2.5 rounded-lg"></div>
        </div>

        <!-- Langkah 2: Hasil Auto-Fill Identitas Warga (Muncul setelah diverifikasi) -->
        <div id="boxIdentitas" class="hidden p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
            <span class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Data Penduduk Terverifikasi Otomatis
            </span>

            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500">Nama Lengkap (Sesuai KTP)</label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       readonly
                       class="block w-full rounded-xl border-slate-200 bg-slate-100/80 font-bold text-slate-800 text-sm py-2 px-3 cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-slate-600">
                <div class="bg-white p-2.5 rounded-lg border border-slate-200/60">
                    <span class="block text-[11px] text-slate-400">Tempat, Tgl Lahir:</span>
                    <span id="labelTtl" class="font-semibold text-slate-700 block mt-0.5">-</span>
                </div>
                <div class="bg-white p-2.5 rounded-lg border border-slate-200/60">
                    <span class="block text-[11px] text-slate-400">Jenis Kelamin:</span>
                    <span id="labelJk" class="font-semibold text-slate-700 block mt-0.5">-</span>
                </div>
                <div class="sm:col-span-2 bg-white p-2.5 rounded-lg border border-slate-200/60">
                    <span class="block text-[11px] text-slate-400">Alamat Domisili:</span>
                    <span id="labelAlamat" class="font-semibold text-slate-700 block mt-0.5">-</span>
                </div>
            </div>
        </div>

        <!-- Langkah 3: Akun Login (Email & Password) -->
        <div id="boxAkun" class="space-y-3">
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                    Alamat Email Aktif <span class="text-red-500">*</span>
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="username"
                       placeholder="nama@email.com"
                       class="block w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                    Kata Sandi (Password) <span class="text-red-500">*</span>
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="new-password"
                       placeholder="Minimal 8 karakter"
                       class="block w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       required
                       autocomplete="new-password"
                       placeholder="Ketik ulang kata sandi"
                       class="block w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2 px-3">
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                    id="btnSubmitRegister"
                    class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-emerald-600/20 transition">
                Daftar Akun Sekarang
            </button>
        </div>

        <div class="text-center pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnCek = document.getElementById('btnCekNik');
            const btnText = document.getElementById('btnCekText');
            const inputNik = document.getElementById('nik');
            const inputKk = document.getElementById('no_kk');
            const statusDiv = document.getElementById('statusVerifikasi');
            const boxIdentitas = document.getElementById('boxIdentitas');
            const inputName = document.getElementById('name');
            const labelTtl = document.getElementById('labelTtl');
            const labelJk = document.getElementById('labelJk');
            const labelAlamat = document.getElementById('labelAlamat');

            // Cek jika ada old input name
            if (inputName.value) {
                boxIdentitas.classList.remove('hidden');
            }

            btnCek.addEventListener('click', function () {
                const nik = inputNik.value.trim();
                const no_kk = inputKk.value.trim();

                if (nik.length !== 16 || no_kk.length !== 16) {
                    statusDiv.className = 'text-xs p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 block';
                    statusDiv.innerHTML = '⚠️ Masukkan 16 digit NIK dan 16 digit Nomor KK dengan benar sebelum verifikasi.';
                    return;
                }

                btnCek.disabled = true;
                btnText.innerText = 'Memeriksa Data...';
                statusDiv.className = 'text-xs p-2.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 block';
                statusDiv.innerHTML = 'Sedang mencari data ke database kependudukan Desa Saobi...';

                fetch('{{ route("register.check-nik") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ nik: nik, no_kk: no_kk })
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(result => {
                    btnCek.disabled = false;
                    btnText.innerText = 'Verifikasi Ulang';

                    if (result.status === 200 && result.body.success) {
                        const d = result.body.data;
                        statusDiv.className = 'text-xs p-2.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 block';
                        statusDiv.innerHTML = '✅ <strong>Data Ditemukan!</strong> Identitas Anda otomatis terisi.';

                        // Auto-fill
                        boxIdentitas.classList.remove('hidden');
                        inputName.value = d.nama;
                        labelTtl.innerText = d.tempat_lahir + ', ' + d.tanggal_lahir;
                        labelJk.innerText = d.jenis_kelamin;
                        labelAlamat.innerText = d.alamat + ' (RT ' + d.rt + ' / RW ' + d.rw + ')';
                    } else {
                        statusDiv.className = 'text-xs p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-800 block';
                        statusDiv.innerHTML = '❌ ' + (result.body.message || 'Data tidak ditemukan.');
                        boxIdentitas.classList.add('hidden');
                        inputName.value = '';
                    }
                })
                .catch(err => {
                    btnCek.disabled = false;
                    btnText.innerText = 'Verifikasi NIK & KK Saya';
                    statusDiv.className = 'text-xs p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-800 block';
                    statusDiv.innerHTML = 'Terjadi kesalahan saat memverifikasi. Silakan coba kembali.';
                });
            });
        });
    </script>
</x-guest-layout>
