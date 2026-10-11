<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Proses Permohonan Surat #{{ $suratRequest->id }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pemohon: <span class="font-bold text-slate-700">{{ $suratRequest->user->name ?? '-' }}</span> (NIK: {{ $suratRequest->user->nik ?? '-' }})
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.permohonan.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                    &larr; Kembali ke Daftar
                </a>
                @if ($suratRequest->status === 'completed' && $suratRequest->file_surat)
                    <a href="{{ asset('storage/' . $suratRequest->file_surat) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat / Unduh Dokumen PDF
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Rincian Pemohon & Aksi Terbitkan (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Pemohon -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4 mb-6 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Rincian Permohonan Warga
                        </span>
                        <div>
                            @if ($suratRequest->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Menunggu Verifikasi
                                </span>
                            @elseif (in_array($suratRequest->status, ['processing', 'revision']))
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Sedang Diproses
                                </span>
                            @elseif ($suratRequest->status === 'completed')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Selesai / Terbit
                                </span>
                            @elseif ($suratRequest->status === 'rejected')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase">Jenis Surat</dt>
                            <dd class="mt-1 font-bold text-base text-slate-800">
                                {{ $suratRequest->suratType->nama ?? 'Surat Keterangan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase">Waktu Pengajuan</dt>
                            <dd class="mt-1 font-semibold text-slate-700">
                                {{ $suratRequest->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase">Nama Lengkap Pemohon</dt>
                            <dd class="mt-1 font-bold text-slate-800">
                                {{ $suratRequest->data_pemohon['nama'] ?? $suratRequest->user->name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase">Nomor Induk Kependudukan (NIK)</dt>
                            <dd class="mt-1 font-mono font-bold text-slate-800">
                                {{ $suratRequest->data_pemohon['nik'] ?? $suratRequest->user->nik ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-400 uppercase">Alamat Domisili</dt>
                            <dd class="mt-1 text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ $suratRequest->data_pemohon['alamat'] ?? '-' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-400 uppercase">Keperluan Surat</dt>
                            <dd class="mt-1 text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                {{ $suratRequest->data_pemohon['keperluan'] ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Bagian Penerbitan Surat & Input Nomor Surat Resmi (Hanya jika belum completed) -->
                @if ($suratRequest->status !== 'completed' && $suratRequest->status !== 'rejected')
                    <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-6 sm:p-8">
                        <div class="flex items-center gap-3 border-b border-emerald-100 pb-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-800">Terbitkan Surat Resmi & Kirim ke Warga</h3>
                                <p class="text-xs text-slate-500">Masukkan nomor registrasi surat desa untuk mengenerate otomatis dokumen PDF resmi</p>
                            </div>
                        </div>

                        <form action="{{ route('admin.permohonan.publish', $suratRequest) }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label for="nomor_surat" class="block text-xs font-bold text-slate-700 uppercase">
                                            Nomor Surat Desa <span class="text-red-500">*</span>
                                        </label>
                                        @if($suratRequest->suratType)
                                            <a href="{{ route('admin.jenis-surat.edit', $suratRequest->suratType) }}" target="_blank" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-800 hover:underline">
                                                Ubah Format
                                            </a>
                                        @endif
                                    </div>
                                    <input type="text"
                                           name="nomor_surat"
                                           id="nomor_surat"
                                           value="{{ old('nomor_surat', $nomorSuratDefault ?? '') }}"
                                           placeholder="Ketik nomor surat (misal: 470/01/{{ $suratRequest->suratType->kode ?? 'DS' }}/{{ date('Y') }})"
                                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3 font-mono font-medium text-slate-800"
                                           required>
                                    <p class="text-[11px] text-emerald-700 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Otomatis terisi dari template <strong>{{ $suratRequest->suratType->nama ?? 'surat' }}</strong>. Anda tetap bisa mengeditnya manual jika diperlukan.</span>
                                    </p>
                                    @error('nomor_surat')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="tanggal_surat" class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                        Tanggal Surat Diterbitkan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date"
                                           name="tanggal_surat"
                                           id="tanggal_surat"
                                           value="{{ old('tanggal_surat', date('Y-m-d')) }}"
                                           class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5 px-3"
                                           required>
                                    @error('tanggal_surat')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Info Penandatangan Kades -->
                            <div class="p-3.5 bg-emerald-50/70 rounded-xl border border-emerald-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                                <div>
                                    <span class="text-emerald-800 font-medium">Tertanda Atas Nama:</span>
                                    <strong class="text-emerald-950">{{ $villageSetting->nama_kepala_desa ?? 'Kepala Desa Saobi' }}</strong>
                                    <span class="text-slate-500">(NIP: {{ $villageSetting->nip_kepala_desa ?? '-' }})</span>
                                </div>
                                <a href="{{ route('admin.settings.index') }}" class="text-emerald-700 font-bold hover:underline">
                                    Ubah Profil Kades &rarr;
                                </a>
                            </div>

                            <button type="submit"
                                    onclick="return confirm('Apakah nomor surat dan tanggal sudah benar? Dokumen PDF resmi akan langsung dibuat dan dikirim ke warga.')"
                                    class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Terbitkan Surat & Generate Dokumen PDF
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Info Dokumen Selesai -->
                    <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-4 sm:p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Surat Resmi Telah Diterbitkan</span>
                                <h4 class="text-base sm:text-lg font-mono font-bold text-slate-800 mt-1">Nomor: {{ $suratRequest->nomor_surat }}</h4>
                                <p class="text-xs text-slate-500 mt-1">
                                    Diterbitkan pada {{ $suratRequest->tanggal_surat ? $suratRequest->tanggal_surat->translatedFormat('d F Y') : '-' }} &bull; Selesai: {{ $suratRequest->completed_at ? $suratRequest->completed_at->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                            @if ($suratRequest->file_surat)
                                <a href="{{ asset('storage/' . $suratRequest->file_surat) }}"
                                   target="_blank"
                                   class="w-full sm:w-auto text-center justify-center inline-flex items-center gap-1.5 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                                    Unduh File PDF
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan: Update Status & Timeline (1 Kolom) -->
            <div class="space-y-6">
                <!-- Form Update Status Admin (Hanya jika belum completed) -->
                @if ($suratRequest->status !== 'completed')
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
                            Ubah Status Permohonan
                        </h3>

                        <form action="{{ route('admin.permohonan.status', $suratRequest) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block text-xs font-bold text-slate-700 mb-1">Pilih Status Baru</label>
                                <select name="status" id="status" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs py-2 px-3">
                                    <option value="processing" @selected($suratRequest->status === 'processing')>Sedang Diproses (processing)</option>
                                    <option value="revision" @selected($suratRequest->status === 'revision')>Perlu Revisi Warga (revision)</option>
                                    <option value="rejected" @selected($suratRequest->status === 'rejected')>Tolak Permohonan (rejected)</option>
                                </select>
                            </div>

                            <div>
                                <label for="catatan_admin" class="block text-xs font-bold text-slate-700 mb-1">Catatan / Alasan (Opsional)</label>
                                <textarea name="catatan_admin"
                                          id="catatan_admin"
                                          rows="3"
                                          placeholder="Tulis alasan jika permohonan ditolak atau perlu revisi..."
                                          class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 text-xs p-2.5">{{ old('catatan_admin', $suratRequest->catatan_admin) }}</textarea>
                                @error('catatan_admin')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-2.5 px-4 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl transition">
                                Simpan Perubahan Status
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Timeline Riwayat -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Aktivitas
                    </h3>

                    <div class="relative border-l-2 border-emerald-200 ms-3 space-y-5">
                        @foreach ($suratRequest->statusHistories as $history)
                            <div class="ms-5 relative">
                                <span class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full bg-emerald-600 ring-4 ring-emerald-50"></span>
                                <h4 class="text-xs font-bold uppercase text-slate-700">
                                    {{ $history->status }}
                                </h4>
                                <p class="text-[11px] text-slate-400">
                                    {{ $history->created_at->translatedFormat('d M Y, H:i') }} &bull; Oleh: {{ $history->user->name ?? 'Sistem' }}
                                </p>
                                @if ($history->catatan)
                                    <p class="text-xs text-slate-600 mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                        {{ $history->catatan }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
