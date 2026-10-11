<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->suratType->nama ?? 'Surat Keterangan' }} - {{ $nomorSurat }}</title>
    <style>
        @page {
            margin: 2cm 2.5cm 2cm 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            position: relative;
        }
        .header h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .garis-kop {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 8px;
            margin-bottom: 20px;
        }
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }
        .judul-surat .nama {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin: 0;
        }
        .judul-surat .nomor {
            font-size: 11pt;
            margin: 3px 0 0 0;
        }
        .content {
            text-align: justify;
        }
        .table-data {
            margin: 15px 0 15px 25px;
            width: 90%;
        }
        .table-data td {
            padding: 3px 0;
            vertical-align: top;
        }
        .table-data td.label {
            width: 140px;
        }
        .table-data td.colon {
            width: 15px;
            text-align: center;
        }
        .footer {
            margin-top: 35px;
            width: 100%;
        }
        .ttd-box {
            float: right;
            width: 260px;
            text-align: center;
        }
        .ttd-space {
            height: 75px;
            position: relative;
        }
        .ttd-img {
            max-height: 70px;
            max-width: 160px;
            display: block;
            margin: 0 auto;
        }
        .nama-kades {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            text-transform: uppercase;
        }
        .nip-kades {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI DESA SAOBI -->
    <div class="header">
        <h3>Pemerintah Kabupaten Sumenep</h3>
        <h3>Kecamatan Kangayan</h3>
        <h2>Pemerintah Desa Saobi</h2>
        <p>{{ $desa->alamat ?? 'Jl. Pelabuhan Saobi, Pulau Saobi, Kec. Kangayan, Kab. Sumenep, Jawa Timur 69491' }}</p>
    </div>
    <div class="garis-kop"></div>

    <!-- JUDUL SURAT -->
    <div class="judul-surat">
        <p class="nama">{{ $surat->suratType->nama ?? 'Surat Keterangan' }}</p>
        <p class="nomor">Nomor: {{ $nomorSurat }}</p>
    </div>

    <!-- ISI SURAT -->
    <div class="content">
        <p>
            Yang bertanda tangan di bawah ini Kepala Desa Saobi, Kecamatan Kangayan, Kabupaten Sumenep, menerangkan dengan sebenarnya bahwa:
        </p>

        <table class="table-data">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td><strong>{{ $surat->data_pemohon['nama'] ?? $surat->user->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="colon">:</td>
                <td>{{ $surat->data_pemohon['nik'] ?? $surat->user->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td>{{ $surat->data_pemohon['alamat'] ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Keperluan</td>
                <td class="colon">:</td>
                <td>{{ $surat->data_pemohon['keperluan'] ?? '-' }}</td>
            </tr>
        </table>

        <p>
            Nama yang bersangkutan di atas adalah benar-benar warga penduduk yang berdomisili di Desa Saobi, Kecamatan Kangayan, Kabupaten Sumenep.
        </p>
        <p>
            Surat keterangan ini diberikan kepada yang bersangkutan untuk dipergunakan sebagaimana mestinya.
        </p>
        <p>
            Demikian surat keterangan ini kami buat dengan sebenarnya dan agar dapat dipergunakan sebagaimana mestinya oleh yang berkepentingan.
        </p>
    </div>

    <!-- TANDA TANGAN KEPALA DESA -->
    <div class="footer">
        <div class="ttd-box">
            <p style="margin: 0;">Saobi, {{ $tanggalSurat->translatedFormat('d F Y') }}</p>
            <p style="margin: 2px 0 0 0; font-weight: bold;">Kepala Desa Saobi</p>
            
            <div class="ttd-space">
                @if (!empty($desa->gambar_tanda_tangan) && file_exists(public_path('storage/' . $desa->gambar_tanda_tangan)))
                    <img class="ttd-img" src="{{ public_path('storage/' . $desa->gambar_tanda_tangan) }}" alt="Tanda Tangan">
                @endif
            </div>

            <p class="nama-kades">{{ $desa->nama_kepala_desa ?? 'KEPALA DESA SAOBI' }}</p>
            <p class="nip-kades">NIP. {{ $desa->nip_kepala_desa ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
