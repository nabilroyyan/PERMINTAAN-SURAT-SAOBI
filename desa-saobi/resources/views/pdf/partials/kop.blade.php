@php
    $kopPath = public_path('images/kop_surat_saobi.jpg');
    $kopBase64 = file_exists($kopPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($kopPath)) : null;
@endphp

@if ($kopBase64)
    <img src="{{ $kopBase64 }}" alt="Kop Surat {{ $desa->nama_desa ?? 'Desa Saobi' }}"
         style="width: 100%; display: block; margin: 0 auto 12px auto;">
@else
    <div style="text-align: center;">
        <div style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Pemerintah Kabupaten Sumenep</div>
        <div style="margin: 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Kecamatan Kangayan</div>
        <div style="margin: 2px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px;">{{ $desa->nama_desa ?? 'Pemerintah Desa Saobi' }}</div>
        <div style="margin: 0; font-size: 10pt; font-style: italic;">{{ $desa->alamat ?? '' }}</div>
    </div>
    <div style="border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 8px; margin-bottom: 20px;"></div>
@endif
