<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">
                    Permohonan Aktivasi Akun
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Setujui permohonan warga yang NIK-nya terdaftar namun belum memiliki akun. Akun dibuat memakai email & kata sandi yang diajukan warga.
                </p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold rounded-xl shadow-sm transition">
                &larr; Dashboard Admin
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6"
         x-data="{
             rejectOpen: false,
             rejectAction: '',
             rejectNama: '',
             bukaTolak(action, nama) { this.rejectAction = action; this.rejectNama = nama; this.rejectOpen = true; }
         }">

        <!-- Tabs Status -->
        <div class="flex items-center gap-2 flex-wrap">
            @php
                $tabs = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
            @endphp
            @foreach ($tabs as $key => $label)
                <a href="{{ route('admin.aktivasi-akun.index', ['status' => $key]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition border
                          {{ $status === $key ? 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-600/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                    {{ $label }}
                    @if ($key === 'pending' && $pendingCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $status === $key ? 'bg-amber-400 text-slate-900' : 'bg-amber-100 text-amber-700' }}">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Tabel Permohonan -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if ($requests->isEmpty())
                <div class="p-16 text-center text-slate-500 text-sm">
                    Tidak ada permohonan aktivasi akun untuk status ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-4">Pemohon</th>
                                <th class="px-5 py-4">Email Diajukan</th>
                                <th class="px-5 py-4">Kata Sandi Diajukan</th>
                                <th class="px-5 py-4">Diajukan</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($requests as $item)
                                <tr class="hover:bg-emerald-50/30 transition align-top">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->nama ?? '-' }}</div>
                                        <div class="text-xs font-mono text-slate-400">NIK: {{ $item->nik ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-xs font-mono text-slate-600">{{ $item->email ?? '-' }}</td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="font-mono text-xs px-2 py-1 rounded-md bg-slate-100 text-slate-700 font-bold select-all">{{ $item->password_plain }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                        {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        @if ($item->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                        @elseif ($item->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Diaktifkan</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">Ditolak</span>
                                        @endif
                                        @if ($item->handled_at)
                                            <div class="text-[11px] text-slate-400 mt-1">
                                                oleh {{ $item->handler->name ?? 'Sistem' }}<br>
                                                {{ $item->handled_at->translatedFormat('d M Y, H:i') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($item->status === 'pending')
                                            <div class="flex flex-wrap items-center gap-2">
                                                <form action="{{ route('admin.aktivasi-akun.approve', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            onclick="return confirm('Aktifkan akun warga ini dengan email & kata sandi yang diajukan?')"
                                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition whitespace-nowrap">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        Setujui &amp; Aktifkan
                                                    </button>
                                                </form>
                                                <button type="button"
                                                        @click="bukaTolak('{{ route('admin.aktivasi-akun.reject', $item) }}', '{{ addslashes($item->nama ?? 'pemohon') }}')"
                                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold text-xs rounded-xl transition whitespace-nowrap">
                                                    Tolak
                                                </button>
                                            </div>
                                        @else
                                            <p class="text-xs text-slate-500 max-w-xs">{{ $item->catatan ?: '-' }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($requests->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $requests->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Modal Tolak -->
        <div x-show="rejectOpen"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4"
             style="display: none;">
            <div @click.away="rejectOpen = false"
                 class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 sm:p-7 space-y-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tolak Permohonan Aktivasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Menolak permohonan aktivasi untuk <strong x-text="rejectNama"></strong>.</p>
                </div>
                <form :action="rejectAction" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan" id="catatan" rows="3" required maxlength="1000"
                                  placeholder="Tulis alasan penolakan..."
                                  class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 text-sm p-3"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2.5">
                        <button type="button" @click="rejectOpen = false"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs shadow-md shadow-red-600/20 transition">
                            Tolak Permohonan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
