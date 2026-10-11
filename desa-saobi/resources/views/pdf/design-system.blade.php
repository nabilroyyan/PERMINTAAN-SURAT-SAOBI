<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Design System - Layanan Surat Desa Saobi</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            @bottom-right {
                content: counter(page);
            }
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
        }

        /* Cover Page */
        .cover {
            text-align: center;
            padding-top: 80px;
            padding-bottom: 60px;
        }
        .cover-badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .cover-title {
            font-size: 26pt;
            font-weight: 800;
            color: #064e3b;
            line-height: 1.2;
            margin: 0 0 15px 0;
            letter-spacing: -0.5px;
        }
        .cover-subtitle {
            font-size: 13pt;
            color: #475569;
            margin: 0 0 40px 0;
            font-weight: 400;
        }
        .cover-divider {
            width: 80px;
            height: 4px;
            background-color: #059669;
            margin: 0 auto 40px auto;
            border-radius: 2px;
        }
        .cover-meta {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            width: 80%;
            margin: 0 auto;
            text-align: left;
        }
        .cover-meta table {
            width: 100%;
            font-size: 10pt;
        }
        .cover-meta td {
            padding: 4px 8px;
        }
        .cover-meta .label {
            color: #64748b;
            font-weight: bold;
            width: 35%;
        }
        .cover-meta .value {
            color: #0f172a;
            font-weight: 600;
        }

        /* Typography & Headings */
        h1 {
            color: #064e3b;
            font-size: 18pt;
            border-bottom: 2px solid #059669;
            padding-bottom: 8px;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: 800;
        }
        h2 {
            color: #047857;
            font-size: 13pt;
            margin-top: 22px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        h3 {
            color: #1e293b;
            font-size: 11pt;
            margin-top: 15px;
            margin-bottom: 8px;
            font-weight: 700;
        }
        p {
            margin: 0 0 10px 0;
            color: #334155;
            font-size: 10pt;
            text-align: justify;
        }

        /* Header & Footer */
        .header-doc {
            position: fixed;
            top: -15mm;
            left: 0;
            right: 0;
            height: 10mm;
            font-size: 8pt;
            color: #94a3b8;
            border-bottom: 1px solid #e2e8f0;
            line-height: 10mm;
        }
        .footer-doc {
            position: fixed;
            bottom: -15mm;
            left: 0;
            right: 0;
            height: 10mm;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            line-height: 10mm;
        }
        .footer-doc .right {
            float: right;
        }

        /* Color Swatches Grid */
        table.color-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 20px 0;
        }
        table.color-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 9pt;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            font-weight: 700;
        }
        table.color-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
            vertical-align: middle;
        }
        .color-box {
            display: inline-block;
            width: 32px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(0,0,0,0.1);
            vertical-align: middle;
            margin-right: 8px;
        }

        /* Badges Sample */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8.5pt;
            font-weight: bold;
            margin-right: 5px;
        }
        .badge-pending {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .badge-process {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .badge-completed {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .badge-rejected {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .badge-admin {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Button Samples */
        .btn {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 9pt;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            margin-right: 6px;
        }
        .btn-primary {
            background-color: #059669;
            color: #ffffff;
            border: 1px solid #047857;
        }
        .btn-secondary {
            background-color: #ffffff;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .btn-dark {
            background-color: #1e293b;
            color: #ffffff;
            border: 1px solid #0f172a;
        }
        .btn-danger {
            background-color: #dc2626;
            color: #ffffff;
            border: 1px solid #b91c1c;
        }

        /* Tables & Component Specs */
        table.spec-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 18px 0;
        }
        table.spec-table th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 9pt;
            font-weight: bold;
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        table.spec-table td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
        }
        .code {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f1f5f9;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8.5pt;
            color: #0f172a;
        }

        /* Info Callout */
        .callout {
            background-color: #ecfdf5;
            border-left: 4px solid #059669;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }
        .callout-title {
            font-weight: bold;
            color: #064e3b;
            font-size: 10pt;
            margin-bottom: 4px;
        }
        .callout-desc {
            color: #047857;
            font-size: 9pt;
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Header & Footer for All Pages -->
    <div class="header-doc">
        <span>SISTEM LAYANAN SURAT DESA SAOBI &bull; PANDUAN DESIGN SYSTEM v1.0</span>
    </div>
    <div class="footer-doc">
        <span>Pemerintah Desa Saobi, Kec. Kangayan, Kab. Sumenep</span>
        <span class="right">Design System Specification</span>
    </div>

    <!-- HALAMAN 1: COVER -->
    <div class="cover">
        <div class="cover-badge">Dokumentasi Standar UI/UX</div>
        <h1 class="cover-title">DESIGN SYSTEM<br>LAYANAN SURAT ONLINE</h1>
        <p class="cover-subtitle">Pedoman Identitas Visual, Token Desain, Tipografi, dan Spesifikasi Komponen</p>
        <div class="cover-divider"></div>

        <div class="cover-meta">
            <table>
                <tr>
                    <td class="label">Nama Proyek:</td>
                    <td class="value">Sistem Informasi Layanan Surat Mandiri Online</td>
                </tr>
                <tr>
                    <td class="label">Instansi:</td>
                    <td class="value">Pemerintah Desa Saobi, Kecamatan Kangayan, Sumenep</td>
                </tr>
                <tr>
                    <td class="label">Tema Visual:</td>
                    <td class="value">Clean White & Emerald Green (Nuansa Alam Pesisir & Integritas)</td>
                </tr>
                <tr>
                    <td class="label">Framework CSS:</td>
                    <td class="value">Tailwind CSS 3.4+ & Google Fonts Inter</td>
                </tr>
                <tr>
                    <td class="label">Versi Dokumen:</td>
                    <td class="value">1.0 (Oktober 2026) &bull; Production Standard</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- HALAMAN 2: PRINSIP & FILOSOFI DESAIN -->
    <h1>1. Filosofi & Prinsip Desain</h1>
    
    <p>
        Design System ini disusun sebagai acuan baku perancangan antarmuka (UI) dan pengalaman pengguna (UX) pada website <strong>Layanan Surat Online Desa Saobi</strong>. Sistem ini memastikan konsistensi visual, efisiensi pengembangan, aksesibilitas prima bagi seluruh kalangan warga desa, serta kejelasan informasi administrasi publik.
    </p>

    <div class="callout">
        <div class="callout-title">Prinsip Inti: "Bersih, Transparan, dan Ramah Warga"</div>
        <p class="callout-desc">
            Seluruh interaksi dirancang agar meminimalkan kebingungan warga saat mengajukan permohonan surat, mempercepat proses verifikasi oleh perangkat desa, serta menampilkan status berkas secara langsung dan akurat.
        </p>
    </div>

    <h2>4 Pilar Utama Desain:</h2>
    <table class="spec-table">
        <tr>
            <th style="width: 25%;">Pilar</th>
            <th style="width: 35%;">Penjelasan</th>
            <th style="width: 40%;">Penerapan pada Sistem</th>
        </tr>
        <tr>
            <td><strong>1. Clarity (Kejelasan)</strong></td>
            <td>Informasi disajikan tanpa kerumitan visual, formulir ringkas, dan instruksi mudah dipahami.</td>
            <td>Label input jelas, validasi real-time, dan contoh nomor surat tertera langsung.</td>
        </tr>
        <tr>
            <td><strong>2. Trustworthy (Integritas)</strong></td>
            <td>Menampilkan citra resmi pemerintahan desa yang kredibel, aman, dan berwibawa.</td>
            <td>Warna dasar Emerald Green yang melambangkan kemakmuran, kop surat resmi, dan TTD elektronik.</td>
        </tr>
        <tr>
            <td><strong>3. Efficiency (Kecepatan)</strong></td>
            <td>Mengurangi langkah berulang bagi warga dan mempermudah kerja admin dalam memproses berkas.</td>
            <td>Otomatisasi nomor surat dari template, import Excel warga massal, dan 1-click terbitkan PDF.</td>
        </tr>
        <tr>
            <td><strong>4. Responsive (Aksesibel)</strong></td>
            <td>Pengalaman nyaman digunakan pada layar smartphone (ponsel warga) hingga PC kantor desa.</td>
            <td>Layout adaptif, ukuran tombol sentuh minimum 44px, dan top navbar responsif.</td>
        </tr>
    </table>

    <!-- HALAMAN 2: SISTEM WARNA -->
    <h1>2. Sistem & Token Warna (Color Palette)</h1>
    <p>
        Palet warna menggunakan perpaduan dominan <strong>Putih Bersih (Canvas)</strong> dan <strong>Hijau Zamrud (Emerald Green)</strong> sebagai warna identitas utama desa, dilengkapi warna fungsional untuk status permohonan.
    </p>

    <h3>A. Warna Utama (Primary Brand Spectrum)</h3>
    <table class="color-table">
        <tr>
            <th>Warna</th>
            <th>Nama Token</th>
            <th>HEX Code</th>
            <th>RGB</th>
            <th>Penggunaan</th>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #059669;"></span> <strong>Primary 600</strong></td>
            <td><span class="code">emerald-600</span></td>
            <td><span class="code">#059669</span></td>
            <td>5, 150, 105</td>
            <td>Tombol aksi utama, navbar active state, link penting.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #047857;"></span> <strong>Primary Dark 700</strong></td>
            <td><span class="code">emerald-700</span></td>
            <td><span class="code">#047857</span></td>
            <td>4, 120, 87</td>
            <td>State hover tombol, judul teks penting, heading.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #064e3b;"></span> <strong>Primary Deep 900</strong></td>
            <td><span class="code">emerald-900</span></td>
            <td><span class="code">#064e3b</span></td>
            <td>6, 78, 59</td>
            <td>Judul besar, identitas brand header.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #ecfdf5;"></span> <strong>Surface Light 50</strong></td>
            <td><span class="code">emerald-50</span></td>
            <td><span class="code">#ecfdf5</span></td>
            <td>236, 253, 245</td>
            <td>Latar belakang kartu aktif, badge, notifikasi sukses.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #a7f3d0;"></span> <strong>Border Light 200</strong></td>
            <td><span class="code">emerald-200</span></td>
            <td><span class="code">#a7f3d0</span></td>
            <td>167, 243, 208</td>
            <td>Border kartu fitur, garis aksen pembatas.</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- HALAMAN 3: WARNA STATUS & NETRAL -->
    <h3>B. Warna Status Semantik (Status Badges)</h3>
    <table class="color-table">
        <tr>
            <th>Status Permohonan</th>
            <th>Warna Background</th>
            <th>Warna Teks</th>
            <th>Border</th>
            <th>Contoh Badge</th>
        </tr>
        <tr>
            <td><strong>Menunggu Verifikasi</strong></td>
            <td><span class="code">#fffbeb</span> (amber-50)</td>
            <td><span class="code">#b45309</span> (amber-700)</td>
            <td>amber-200</td>
            <td><span class="badge badge-pending">Menunggu Verifikasi</span></td>
        </tr>
        <tr>
            <td><strong>Sedang Diproses</strong></td>
            <td><span class="code">#eff6ff</span> (blue-50)</td>
            <td><span class="code">#1d4ed8</span> (blue-700)</td>
            <td>blue-200</td>
            <td><span class="badge badge-process">Sedang Diproses</span></td>
        </tr>
        <tr>
            <td><strong>Selesai / Terbit</strong></td>
            <td><span class="code">#ecfdf5</span> (emerald-50)</td>
            <td><span class="code">#047857</span> (emerald-700)</td>
            <td>emerald-200</td>
            <td><span class="badge badge-completed">Selesai / Terbit</span></td>
        </tr>
        <tr>
            <td><strong>Ditolak / Batal</strong></td>
            <td><span class="code">#fef2f2</span> (red-50)</td>
            <td><span class="code">#b91c1c</span> (red-700)</td>
            <td>red-200</td>
            <td><span class="badge badge-rejected">Ditolak</span></td>
        </tr>
        <tr>
            <td><strong>Akun Petugas Admin</strong></td>
            <td><span class="code">#fef3c7</span> (amber-100)</td>
            <td><span class="code">#92400e</span> (amber-800)</td>
            <td>amber-200</td>
            <td><span class="badge badge-admin">Petugas Admin</span></td>
        </tr>
    </table>

    <h3>C. Spektrum Netral (Canvas & Text Neutrals)</h3>
    <table class="color-table">
        <tr>
            <th>Warna</th>
            <th>Nama Token</th>
            <th>HEX Code</th>
            <th>Peruntukan</th>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #f8fafc;"></span> <strong>Canvas Slate-50</strong></td>
            <td><span class="code">bg-slate-50</span></td>
            <td><span class="code">#f8fafc</span></td>
            <td>Latar belakang dasar seluruh halaman web.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #ffffff;"></span> <strong>Card White</strong></td>
            <td><span class="code">bg-white</span></td>
            <td><span class="code">#ffffff</span></td>
            <td>Latar kartu, panel tabel, form, modal pop-up.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #e2e8f0;"></span> <strong>Border Slate-200</strong></td>
            <td><span class="code">border-slate-200</span></td>
            <td><span class="code">#e2e8f0</span></td>
            <td>Garis pembatas kartu, input border, garis tabel.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #64748b;"></span> <strong>Muted Slate-500</strong></td>
            <td><span class="code">text-slate-500</span></td>
            <td><span class="code">#64748b</span></td>
            <td>Teks pendukung, label deskripsi, metadata waktu.</td>
        </tr>
        <tr>
            <td><span class="color-box" style="background-color: #1e293b;"></span> <strong>Heading Slate-800</strong></td>
            <td><span class="code">text-slate-800</span></td>
            <td><span class="code">#1e293b</span></td>
            <td>Teks utama, judul kolom tabel, angka statistik.</td>
        </tr>
    </table>

    <!-- HALAMAN 3: TIPOGRAFI -->
    <h1>3. Sistem Tipografi (Typography)</h1>
    <p>
        Tipografi menggunakan keluarga huruf sans-serif modern <strong>Inter</strong> yang dirancang khusus untuk keterbacaan tinggi di berbagai resolusi layar, dipadukan dengan <strong>Font Monospace</strong> untuk data angka resmi kependudukan (NIK, Nomor KK, Nomor Surat, Password).
    </p>

    <table class="spec-table">
        <tr>
            <th>Elemen Tipografi</th>
            <th>Ukuran / Weight</th>
            <th>Line Height</th>
            <th>Contoh & Penerapan</th>
        </tr>
        <tr>
            <td><strong>Page Heading (H1)</strong></td>
            <td>24px (1.5rem) / Extra-Bold (800)</td>
            <td>1.25</td>
            <td>Judul halaman ("Dashboard Administrator Desa Saobi")</td>
        </tr>
        <tr>
            <td><strong>Section Heading (H2)</strong></td>
            <td>18px (1.125rem) / Bold (700)</td>
            <td>1.35</td>
            <td>Judul kartu / blok panel ("Permohonan Surat Terbaru")</td>
        </tr>
        <tr>
            <td><strong>Card Subheading (H3)</strong></td>
            <td>14px (0.875rem) / Semi-Bold (600)</td>
            <td>1.4</td>
            <td>Label kartu statistik, header panel form.</td>
        </tr>
        <tr>
            <td><strong>Body Text (Regular)</strong></td>
            <td>14px (0.875rem) / Normal (400)</td>
            <td>1.5</td>
            <td>Paragraf penjelasan, keterangan umum.</td>
        </tr>
        <tr>
            <td><strong>Data Kependudukan</strong></td>
            <td>12px - 14px / <strong>Monospace (Font Mono)</strong></td>
            <td>1.4</td>
            <td>NIK 16 digit (<span class="code">3529012345670001</span>), No. Surat, Password.</td>
        </tr>
        <tr>
            <td><strong>Micro & Metadata</strong></td>
            <td>11px - 12px / Medium (500)</td>
            <td>1.3</td>
            <td>Waktu pembuatan surat ("11 Okt 2026, 17:00 WIB"), footer.</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- HALAMAN 4: KOMPONEN UI UTAMA -->
    <h1>4. Katalog Komponen Antarmuka (UI Components)</h1>

    <h2>A. Tombol Aksi (Buttons)</h2>
    <table class="spec-table">
        <tr>
            <th>Jenis Tombol</th>
            <th>Visualisasi</th>
            <th>Class Tailwind CSS</th>
            <th>Peruntukan</th>
        </tr>
        <tr>
            <td><strong>Primary Button</strong></td>
            <td><span class="btn btn-primary">Simpan / Terbitkan Surat</span></td>
            <td><span class="code">bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl shadow-sm</span></td>
            <td>Aksi simpan, terbitkan surat, filter data.</td>
        </tr>
        <tr>
            <td><strong>Secondary / Outline</strong></td>
            <td><span class="btn btn-secondary">Download Template</span></td>
            <td><span class="code">bg-white border border-emerald-300 text-emerald-700 rounded-xl</span></td>
            <td>Aksi sekunder, unduh berkas template.</td>
        </tr>
        <tr>
            <td><strong>Neutral / Dark</strong></td>
            <td><span class="btn btn-dark">+ Tambah Warga</span></td>
            <td><span class="code">bg-slate-800 hover:bg-slate-900 text-white rounded-xl</span></td>
            <td>Pintasan tambah entitas baru dari master data.</td>
        </tr>
        <tr>
            <td><strong>Danger Button</strong></td>
            <td><span class="btn btn-danger">Hapus / Tolak</span></td>
            <td><span class="code">bg-red-600 hover:bg-red-700 text-white rounded-xl</span></td>
            <td>Tindakan pembatalan atau hapus data permanen.</td>
        </tr>
    </table>

    <h2>B. Komponen Form & Input</h2>
    <p>
        Setiap kolom input dilengkapi label yang jelas, radius sudut melengkung halus (<span class="code">rounded-xl</span>), border netral yang berganti menjadi aksen hijau emerald saat fokus (<span class="code">focus:ring-emerald-500 focus:border-emerald-500</span>), serta penanda kolom wajib (*).
    </p>

    <table class="spec-table">
        <tr>
            <th>Elemen Form</th>
            <th>Spesifikasi Desain</th>
            <th>Fitur Khusus</th>
        </tr>
        <tr>
            <td><strong>Input Teks & NIK</strong></td>
            <td>Padding 10px 12px, border-slate-300, font-mono untuk angka NIK/No KK.</td>
            <td>Format otomatis 16 digit numerik.</td>
        </tr>
        <tr>
            <td><strong>Dropdown / Select</strong></td>
            <td>Tinggi standar 42px, panah indikator, background putih bersih.</td>
            <td>Daftar jenis surat & filter status verifikasi.</td>
        </tr>
        <tr>
            <td><strong>Input File Upload</strong></td>
            <td>Tombol custom emerald (<span class="code">file:bg-emerald-600</span>) dan info batas format file.</td>
            <td>Validasi format dokumen Excel, KTP, dan KK.</td>
        </tr>
        <tr>
            <td><strong>Nomor Surat Otomatis</strong></td>
            <td>Terisi otomatis dari rumus template jenis surat (misal: <span class="code">{NOMOR}/SKU/{TAHUN}</span>).</td>
            <td>Tetap dapat diedit manual oleh Admin jika ada nomor khusus.</td>
        </tr>
    </table>

    <h2>C. Komponen Navigasi (Top Navigation Bar)</h2>
    <p>
        Navigasi ditempatkan secara konsisten di bagian atas layar (Top Bar) dengan konsep <strong>Sticky Header</strong>:
    </p>
    <ul>
        <li><strong>Brand Logo:</strong> Ikon gedung pelayanan dengan gradien hijau zamrud dan tipografi tegas "DESA SAOBI - Layanan Surat Online".</li>
        <li><strong>Menu Links Desktop:</strong> Tab navigasi horizontal dengan garis bawah hijau tebal (<span class="code">border-b-2 border-emerald-600</span>) saat halaman aktif.</li>
        <li><strong>Role Tag & Profil:</strong> Penanda peran pengguna (Badge "Petugas Admin" atau "Warga Desa"), inisial avatar, serta menu dropdown profil & logout.</li>
        <li><strong>Mobile Drawer Menu:</strong> Tombol hamburger intuitif yang membuka menu geser ke bawah secara responsif di smartphone.</li>
    </ul>

    <div class="page-break"></div>

    <!-- HALAMAN 5: ARSITEKTUR INFORMASI & STANDAR SURAT -->
    <h1>5. Alur Pengguna (User Flow) & Standar Dokumen</h1>

    <h2>A. Alur Pelayanan Surat Mandiri:</h2>
    <table class="spec-table">
        <tr>
            <th>Tahap</th>
            <th>Aktor</th>
            <th>Tindakan Sistem</th>
            <th>Status Dokumen</th>
        </tr>
        <tr>
            <td><strong>1. Pengajuan</strong></td>
            <td>Warga Desa</td>
            <td>Warga memilih jenis surat, mengisi keperluan, dan mengunggah berkas syarat (KTP/KK).</td>
            <td><span class="badge badge-pending">Menunggu Verifikasi</span></td>
        </tr>
        <tr>
            <td><strong>2. Verifikasi</strong></td>
            <td>Petugas Admin</td>
            <td>Admin memeriksa kesesuaian data NIK dan berkas persyaratan di dashboard.</td>
            <td><span class="badge badge-process">Sedang Diproses</span></td>
        </tr>
        <tr>
            <td><strong>3. Penerbitan</strong></td>
            <td>Petugas Admin</td>
            <td>Sistem menyusun nomor surat otomatis sesuai template, admin konfirmasi & terbitkan.</td>
            <td><span class="badge badge-completed">Selesai / Terbit</span></td>
        </tr>
        <tr>
            <td><strong>4. Unduh PDF</strong></td>
            <td>Warga / Admin</td>
            <td>Dokumen PDF resmi siap cetak langsung dapat diunduh lengkap dengan tanda tangan kades.</td>
            <td><strong>Dokumen PDF Terbit</strong></td>
        </tr>
    </table>

    <h2>B. Standar Dokumen PDF Surat Resmi (Cetak Kertas A4):</h2>
    <table class="spec-table">
        <tr>
            <th>Bagian Surat</th>
            <th>Standar Format & Tipografi</th>
        </tr>
        <tr>
            <td><strong>Kop Surat Resmi</strong></td>
            <td>Logo Kabupaten Sumenep di sisi kiri, teks: "PEMERINTAH KABUPATEN SUMENEP &bull; KECAMATAN KANGAYAN &bull; KEPALA DESA SAOBI", alamat kantor desa, dan garis ganda tebal.</td>
        </tr>
        <tr>
            <td><strong>Judul & Nomor Surat</strong></td>
            <td>Huruf kapital tebal bergaris bawah (misal: <u>SURAT KETERANGAN USAHA</u>), di bawahnya nomor surat resmi format agenda desa.</td>
        </tr>
        <tr>
            <td><strong>Isi & Data Warga</strong></td>
            <td>Tabel data pemohon: Nama Lengkap, NIK, Tempat/Tgl Lahir, Jenis Kelamin, Agama, Pekerjaan, Alamat, dan Maksud/Keperluan.</td>
        </tr>
        <tr>
            <td><strong>Kolom Tanda Tangan</strong></td>
            <td>Tempat & tanggal penetapan ("Saobi, [Tanggal]"), jabatan "Kepala Desa Saobi", stempel basah desa, barcode/QR verifikasi digital, tanda tangan resmi, dan nama pejabat beserta NIP.</td>
        </tr>
    </table>

    <div class="callout" style="margin-top: 30px;">
        <div class="callout-title">Penyimpanan & Hak Cipta Design System</div>
        <p class="callout-desc">
            Dokumen ini merupakan standar resmi implementasi antarmuka website Sistem Layanan Surat Desa Saobi, Kecamatan Kangayan, Kabupaten Sumenep. Seluruh pengembangan komponen di masa mendatang wajib merujuk pada spesifikasi token warna, tipografi, dan tata letak yang tercantum di sini.
        </p>
    </div>

</body>
</html>
