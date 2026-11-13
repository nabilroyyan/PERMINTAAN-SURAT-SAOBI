<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->suratType->nama ?? 'Surat Keterangan' }} - {{ $nomorSurat }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 1.4cm 2.2cm 1.8cm 2.2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5pt;
            line-height: 1.45;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
        }
        .judul-surat {
            text-align: center;
            margin: 16px 0 18px 0;
        }
        .judul-surat .nama {
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .judul-surat .nomor {
            font-size: 10.5pt;
            margin: 2px 0 0 0;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin: 0 0 8px 0;
        }
        .table-pemohon {
            width: 100%;
            margin: 6px 0 14px 0;
        }
        .table-pemohon td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .table-pemohon td.label {
            width: 190px;
        }
        .table-pemohon td.colon {
            width: 16px;
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-box {
            float: right;
            width: 240px;
            text-align: center;
            margin-right: 10px;
        }
        .ttd-space {
            height: 68px;
            position: relative;
        }
        .ttd-img {
            max-height: 65px;
            max-width: 150px;
            display: block;
            margin: 0 auto;
        }
        .nama-kades {
            font-weight: bold;
            font-size: 10.5pt;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .nip-kades {
            margin: 2px 0 0 0;
            font-size: 10.5pt;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI (meniru tampilan Surat Keterangan Domisili) -->
    @include('pdf.partials.kop')

    <!-- JUDUL SURAT -->
    <div class="judul-surat">
        <div class="nama">{{ $surat->suratType->nama ?? 'Surat Keterangan' }}</div>
        <div class="nomor">Nomor : {{ $nomorSurat }}</div>
    </div>

    <!-- ISI SURAT (DARI TEMPLATE DINAMIS) -->
    <div class="content">
        {!! $isiSurat !!}
    </div>

    <!-- FOOTER: QR VERIFIKASI (KIRI) & TANDA TANGAN KEPALA DESA (KANAN) -->
    <div class="footer">
        @include('pdf.partials.qr-verifikasi')

        <div class="ttd-box">
            <p style="margin: 0;">Saobi, {{ $tanggalSurat->locale('id')->translatedFormat('d F Y') }}</p>
            <p style="margin: 1px 0 0 0;">Kepala Desa Saobi</p>

            <div class="ttd-space">
                @if (!empty($desa->gambar_tanda_tangan) && file_exists(public_path('storage/' . $desa->gambar_tanda_tangan)))
                    <img class="ttd-img" src="{{ public_path('storage/' . $desa->gambar_tanda_tangan) }}" alt="Tanda Tangan">
                @endif
            </div>

            <p class="nama-kades">{{ $desa->nama_kepala_desa ?? 'KEPALA DESA SAOBI' }}</p>
            @if (!empty($desa->nip_kepala_desa) && $desa->nip_kepala_desa !== '-')
                <p class="nip-kades">NIP. {{ $desa->nip_kepala_desa }}</p>
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
