<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">
                    Pengajuan Surat Mandiri
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Silakan isi data keperluan permohonan surat administrasi Desa Saobi
                </p>
            </div>
            <div>
                <a href="{{ route('client.dashboard') }}"
                   class="inline-flex items-center justify-center gap-1.5 w-full sm:w-auto px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Kembali ke Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- Header Kartu -->
            <div class="p-4 sm:p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-emerald-100 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Formulir Permohonan Surat Online</h3>
                    <p class="text-xs text-slate-600">Pastikan data diri Anda sudah sesuai dengan KTP & Kartu Keluarga (KK).</p>
                </div>
            </div>

            <!-- Error Notification -->
            @if ($errors->any())
                <div class="p-4 mx-4 sm:mx-6 mt-4 sm:mt-6 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-semibold mb-1 text-xs sm:text-sm">Periksa kembali isian formulir Anda:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('client.surat.store') }}" method="POST" class="p-4 sm:p-6 md:p-8 space-y-5 sm:space-y-6">
                @csrf

                <!-- Informasi Pemohon (Read Only) -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 sm:p-5">
                    <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2.5 sm:mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path>
                        </svg>
                        Data Identitas Pemohon
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div class="bg-white p-3 rounded-lg border border-slate-200/60">
                            <span class="block text-[11px] text-slate-400 font-medium uppercase tracking-wider">Nama Lengkap (KTP)</span>
                            <span class="font-bold text-slate-800 mt-0.5 block truncate">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-200/60">
                            <span class="block text-[11px] text-slate-400 font-medium uppercase tracking-wider">Nomor Induk Kependudukan (NIK)</span>
                            <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ Auth::user()->nik ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Jenis Surat -->
                <div>
                    <label for="surat_type_id" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Pilih Jenis Surat Yang Diajukan <span class="text-red-500">*</span>
                    </label>
                    <select id="surat_type_id"
                            name="surat_type_id"
                            class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm py-2.5 px-3 transition shadow-sm"
                            required>
                        <option value="">-- Pilih Salah Satu Jenis Surat --</option>
                        @foreach ($jenisSurat as $jenis)
                            <option value="{{ $jenis->id }}" @selected(old('surat_type_id') == $jenis->id)>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('surat_type_id')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Domisili -->
                <div>
                    <label for="alamat" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Alamat Domisili Lengkap di Desa Saobi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat"
                              name="alamat"
                              rows="3"
                              class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3 transition shadow-sm"
                              placeholder="Contoh: Dusun Saobi Timur, RT 01 / RW 02, Desa Saobi, Kec. Kangayan"
                              required>{{ old('alamat', Auth::user()->penduduk->alamat ?? '') }}</textarea>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Otomatis terisi dari data kependudukan resmi. Anda dapat menyesuaikan jika diperlukan.</p>
                    @error('alamat')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keperluan Surat -->
                <div>
                    <label for="keperluan" class="block text-xs sm:text-sm font-bold text-slate-700 mb-1.5">
                        Keperluan / Alasan Pengajuan Surat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keperluan"
                              name="keperluan"
                              rows="3"
                              class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs sm:text-sm p-3 transition shadow-sm"
                              placeholder="Contoh: Persyaratan melamar pekerjaan / Permohonan beasiswa kuliah / Bantuan sosial"
                              required>{{ old('keperluan') }}</textarea>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Jelaskan secara singkat tujuan dibuatnya surat keterangan ini.</p>
                    @error('keperluan')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Submit (Responsive Stack on Mobile) -->
                <div class="pt-4 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5 sm:gap-3">
                    <a href="{{ route('client.dashboard') }}"
                       class="w-full sm:w-auto text-center px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs sm:text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto justify-center px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition duration-150 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Permohonan Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
