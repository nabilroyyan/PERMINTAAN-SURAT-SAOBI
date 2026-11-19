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
                    Edit Template: {{ $template->nama }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Perubahan isi template akan otomatis dipakai saat surat diterbitkan untuk jenis surat yang memakainya.
                </p>
            </div>
        </div>
    </x-slot>

    @include('admin.template_surat.partials.editor', [
        'action' => route('admin.template-surat.update', $template),
        'method' => 'PUT',
        'template' => $template,
        'variableGroups' => $variableGroups,
        'defaultKonten' => null,
        'customVariables' => $customVariables,
        'submitLabel' => 'Simpan Perubahan Template',
    ])
</x-app-layout>
