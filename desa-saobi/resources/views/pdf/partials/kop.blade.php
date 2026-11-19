@php
    $logoKiri = $desa?->logoKiriDataUri();
    $logoKanan = $desa?->logoKananDataUri();

    $infoDesa = array_values(array_filter([
        $desa?->alamat,
        filled($desa?->kode_pos) ? 'Kode Pos ' . $desa->kode_pos : null,
        filled($desa?->telepon) ? 'Telp. ' . $desa->telepon : null,
        filled($desa?->email) ? 'Email: ' . $desa->email : null,
        $desa?->website,
    ], fn ($item) => filled($item)));
@endphp

<!-- KOP SURAT DINAMIS: LOGO KIRI - TEKS - LOGO KANAN -->
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        @if ($logoKiri)
            <td style="width: 90px; text-align: center; vertical-align: middle;">
                <img src="{{ $logoKiri }}" alt="Logo Kiri" style="max-height: 80px; max-width: 80px;">
            </td>
        @endif

        <td style="text-align: center; vertical-align: middle;">
            @if (filled($desa?->kabupaten))
                <div style="margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; line-height: 1.25;">
                    Pemerintah Kabupaten {{ $desa->kabupaten }}
                </div>
            @endif

            @if (filled($desa?->kecamatan))
                <div style="margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; line-height: 1.25;">
                    Kecamatan {{ $desa->kecamatan }}
                </div>
            @endif

            <div style="margin: 2px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; line-height: 1.25;">
                {{ $desa?->nama_desa ?? 'Pemerintah Desa' }}
            </div>

            @if (!empty($infoDesa))
                <div style="margin: 0; font-size: 9.5pt; font-style: italic; line-height: 1.3;">
                    {{ implode(' · ', $infoDesa) }}
                </div>
            @endif
        </td>

        @if ($logoKanan)
            <td style="width: 90px; text-align: center; vertical-align: middle;">
                <img src="{{ $logoKanan }}" alt="Logo Kanan" style="max-height: 80px; max-width: 80px;">
            </td>
        @endif
    </tr>
</table>

<div style="border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 8px; margin-bottom: 18px;"></div>
