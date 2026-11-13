@php
    $qrUrl = method_exists($surat, 'verificationUrl') ? $surat->verificationUrl() : null;
    $qrDataUri = $qrUrl ? \App\Support\QrCodeGenerator::svgDataUri($qrUrl, 160, 1) : null;
@endphp

@if ($qrDataUri)
    <div style="float: left; width: 120px; text-align: center;">
        <img src="{{ $qrDataUri }}" alt="QR Verifikasi Keaslian Surat"
             style="width: 86px; height: 86px; display: block; margin: 0 auto;">
        <div style="font-family: Arial, Helvetica, sans-serif; font-size: 7pt; line-height: 1.25; margin-top: 3px; color: #000;">
            Pindai untuk verifikasi keaslian surat
        </div>
    </div>
@endif
