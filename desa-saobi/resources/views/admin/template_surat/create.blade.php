<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.template-surat.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                        ← Kembali ke Daftar Template
                    </a>
                </div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-slate-800 tracking-tight mt-1">
                    Tambah Template Isi Surat
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Susun isi surat menggunakan variabel agar otomatis terisi data pemohon, desa, dan nomor surat.
                </p>
            </div>
        </div>
    </x-slot>

    @include('admin.template_surat.partials.editor', [
        'action' => route('admin.template-surat.store'),
        'method' => 'POST',
        'template' => null,
        'variableGroups' => $variableGroups,
        'defaultKonten' => $defaultKonten,
        'customVariables' => $customVariables,
        'submitLabel' => 'Simpan Template Baru',
    ])
</x-app-layout>
