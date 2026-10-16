<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Domisili - {{ $nomorSurat }}</title>
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
        .kop-img {
            width: 100%;
            display: block;
            margin: 0 auto 12px auto;
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
        .table-data {
            width: 100%;
            margin: 6px 0 14px 0;
        }
        .table-data td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10.5pt;
        }
        .table-data td.label-kades {
            width: 190px;
        }
        .table-data td.label-warga {
            width: 190px;
        }
        .table-data td.colon {
            width: 16px;
            text-align: left;
        }
        .p-intro {
            margin: 0 0 4px 0;
        }
        .p-body {
            margin: 18px 0;
            line-height: 1.5;
            text-align: justify;
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
        .ttd-box .tanggal {
            margin: 0;
            font-size: 10.5pt;
        }
        .ttd-box .jabatan {
            margin: 1px 0 0 0;
            font-size: 10.5pt;
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
    </style>
</head>
<body>
@php
    $kopPath = public_path('images/kop_surat_saobi.jpg');
    $kopBase64 = file_exists($kopPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($kopPath)) : '';

    $penduduk = $surat->user?->penduduk;
    $namaPemohon = $surat->data_pemohon['nama'] ?? $penduduk?->nama_lengkap ?? $surat->user?->name ?? '-';
    $nikPemohon = $surat->data_pemohon['nik'] ?? $penduduk?->nik ?? $surat->user?->nik ?? '-';

    // Tempat & Tanggal Lahir
    $tempatLahir = $surat->data_pemohon['tempat_lahir'] ?? $penduduk?->tempat_lahir ?? 'Sumenep';
    $tglLahirRaw = $surat->data_pemohon['tanggal_lahir'] ?? $penduduk?->tanggal_lahir;
    $tglLahirFormatted = null;
    if ($tglLahirRaw) {
        try {
            $tglLahirCarbon = \Carbon\Carbon::parse($tglLahirRaw);
            $tglLahirFormatted = $tglLahirCarbon->format('d,10,Y');
            // Format eksak tanggal lahir sesuai contoh d,m,Y:
            $tglLahirFormatted = $tglLahirCarbon->format('d,m,Y');
        } catch (\Exception $e) {
            $tglLahirFormatted = $tglLahirRaw;
        }
    }
    $ttl = $tglLahirFormatted ? "{$tempatLahir}, {$tglLahirFormatted}" : $tempatLahir;

    $jenisKelamin = $surat->data_pemohon['jenis_kelamin'] ?? $penduduk?->jenis_kelamin ?? 'Laki-laki';
    $statusPerkawinan = $surat->data_pemohon['status_perkawinan'] ?? $penduduk?->status_perkawinan ?? 'Kawin';
    $agama = $surat->data_pemohon['agama'] ?? $penduduk?->agama ?? 'Islam';
    $pekerjaan = $surat->data_pemohon['pekerjaan'] ?? $penduduk?->pekerjaan ?? 'Wiraswasta';

    // Alamat
    $dusun = $surat->data_pemohon['dusun'] ?? $penduduk?->dusun ?? '';
    $rt = $surat->data_pemohon['rt'] ?? $penduduk?->rt ?? '';
    $rw = $surat->data_pemohon['rw'] ?? $penduduk?->rw ?? '';
    $alamatRaw = $surat->data_pemohon['alamat'] ?? $penduduk?->alamat ?? '';

    if ($dusun) {
        $rtRwStr = ($rt || $rw) ? " RT/RW  " . str_pad($rt ?: '018', 3, '0', STR_PAD_LEFT) . "/" . str_pad($rw ?: '009', 3, '0', STR_PAD_LEFT) : "";
        $alamatBaris1 = "Dusun " . $dusun . $rtRwStr . " Desa Saobi";
        $alamatBaris2 = "Kecamatan Kangayan Kabupaten Sumenep";
        $alamatTinggal = "Dusun " . $dusun . " Desa Saobi Kecamatan Kangayan Kabupaten Sumenep";
    } else {
        $alamatBaris1 = $alamatRaw ?: "Dusun Saobi Desa Saobi";
        $alamatBaris2 = "Kecamatan Kangayan Kabupaten Sumenep";
        $alamatTinggal = "Desa Saobi Kecamatan Kangayan Kabupaten Sumenep";
    }

    $namaKades = $desa->nama_kepala_desa ?? 'HOSAINI, SH';
    $cleanedKades = str_replace([' ', '.'], '', strtoupper($namaKades));
    if ($cleanedKades === 'HOSAINI,SH' || $cleanedKades === 'HOSAINISH') {
        $namaKadesTampil = 'H O S A I N I, S H';
    } else {
        $namaKadesTampil = strtoupper($namaKades);
    }
@endphp

    <!-- KOP SURAT RESMI DESA SAOBI -->
    @if($kopBase64)
        <img class="kop-img" src="{{ $kopBase64 }}" alt="Kop Surat Desa Saobi">
    @endif

    <!-- JUDUL SURAT -->
    <div class="judul-surat">
        <div class="nama">SURAT KETERANGAN DOMISILI</div>
        <div class="nomor">Nomor : {{ $nomorSurat }}</div>
    </div>

    <!-- ISI SURAT -->
    <div class="content">
        <p class="p-intro">Yang bertanda tangan di bawah ini :</p>
        <table class="table-data">
            <tr>
                <td class="label-kades">Nama</td>
                <td class="colon">:</td>
                <td><strong>{{ $desa->nama_kepala_desa ?? 'HOSAINI, SH' }}</strong></td>
            </tr>
            <tr>
                <td class="label-kades">Alamat</td>
                <td class="colon">:</td>
                <td>Saobi Kangayan Sumenep</td>
            </tr>
            <tr>
                <td class="label-kades">Jabatan</td>
                <td class="colon">:</td>
                <td>Kepala Desa Saobi</td>
            </tr>
        </table>

        <p class="p-intro">Menerangkan dengan sebenarnya bahwa:</p>
        <table class="table-data">
            <tr>
                <td class="label-warga">Nama</td>
                <td class="colon">:</td>
                <td>{{ strtoupper($namaPemohon) }}</td>
            </tr>
            <tr>
                <td class="label-warga">NIK</td>
                <td class="colon">:</td>
                <td>{{ $nikPemohon }}</td>
            </tr>
            <tr>
                <td class="label-warga">Tempat / Tanggal Lahir</td>
                <td class="colon">:</td>
                <td>{{ $ttl }}</td>
            </tr>
            <tr>
                <td class="label-warga">Jenis kelamin</td>
                <td class="colon">:</td>
                <td>{{ $jenisKelamin }}</td>
            </tr>
            <tr>
                <td class="label-warga">Status perkawinan</td>
                <td class="colon">:</td>
                <td>{{ $statusPerkawinan }}</td>
            </tr>
            <tr>
                <td class="label-warga">Agama</td>
                <td class="colon">:</td>
                <td>{{ $agama }}</td>
            </tr>
            <tr>
                <td class="label-warga">Pekerjaan</td>
                <td class="colon">:</td>
                <td>{{ $pekerjaan }}</td>
            </tr>
            <tr>
                <td class="label-warga">Alamat</td>
                <td class="colon">:</td>
                <td>{{ $alamatBaris1 }}</td>
            </tr>
            <tr>
                <td class="label-warga"></td>
                <td class="colon">:</td>
                <td>{{ $alamatBaris2 }}</td>
            </tr>
        </table>

        <p class="p-body">
            Adalah benar berdomisili dan bertempat tinggal di {{ $alamatTinggal }}.
        </p>

        <p class="p-body" style="margin-bottom: 25px;">
            Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- TANDA TANGAN KEPALA DESA -->
    <div class="footer">
        <div class="ttd-box">
            <p class="tanggal">Saobi, {{ $tanggalSurat->locale('id')->translatedFormat('d F Y') }}</p>
            <p class="jabatan">Kepala Desa Saobi</p>
            
            <div class="ttd-space">
                {{-- Ruang kosong untuk tanda tangan dan stempel resmi (atau dinamis jika admin mengunggah ttd di pengaturan) --}}
                @if (!empty($desa->gambar_tanda_tangan) && file_exists(public_path('storage/' . $desa->gambar_tanda_tangan)))
                    <img class="ttd-img" src="{{ public_path('storage/' . $desa->gambar_tanda_tangan) }}" alt="Tanda Tangan">
                @endif
            </div>

            <p class="nama-kades">{{ $namaKadesTampil }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
