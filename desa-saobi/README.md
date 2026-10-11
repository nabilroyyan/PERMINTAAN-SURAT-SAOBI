# Sistem Layanan Surat Mandiri Online Desa Saobi
> **Pemerintah Desa Saobi**, Kecamatan Kangayan, Kabupaten Sumenep, Jawa Timur.

Aplikasi web berbasis **Laravel 11** untuk pengelolaan administrasi permohonan surat keterangan warga secara mandiri, cepat, dan transparan. Dilengkapi fitur import master data kependudukan dari Excel, penomoran surat otomatis yang dapat disesuaikan per jenis surat, tanda tangan elektronik kepala desa, serta penerbitan dokumen PDF siap cetak.

---

## 🚀 Fitur Utama

- **Portal Warga (Client):**
  - Pembuatan permohonan surat secara mandiri.
  - Tracking status pengajuan (*Menunggu Verifikasi*, *Diproses*, *Selesai / Terbit*, *Ditolak*).
  - Unduh langsung dokumen surat resmi berformat PDF.
- **Panel Administrator (Petugas Desa):**
  - **Dashboard Statistik:** Ringkasan surat masuk, antrean verifikasi, dan statistik surat terbit.
  - **Master Kependudukan:** Kelola data warga serta import data massal dari file Excel (`.xlsx` / `.csv`) lengkap dengan pembuatan akun login warga secara otomatis.
  - **Pengaturan Jenis & Nomor Surat:** Konfigurasi template penomoran surat otomatis per jenis surat (misal: `470/{NOMOR}/SKU/435.312.108/{TAHUN}`) dengan opsi edit manual saat pemrosesan.
  - **Pengaturan Profil Desa & TTD:** Nama Kepala Desa, NIP, alamat kantor desa, serta unggah tanda tangan & stempel basah resmi.
  - **Penerbitan Surat & PDF Otomatis:** Otomatis menghasilkan cetak surat resmi format PDF.
  - **Dokumentasi Design System:** Tersedia buku pedoman desain resmi dalam format PDF ([`public/docs/Design_System_Desa_Saobi.pdf`](public/docs/Design_System_Desa_Saobi.pdf)).

---

## 📋 Prasyarat Sistem (Prerequisites)

Pastikan komputer/server Anda telah terpasang software berikut:

- **PHP** >= 8.2 (dengan ekstensi `pdo`, `mbstring`, `openssl`, `gd`, `zip`, `fileinfo`)
- **Composer** (Package Manager PHP)
- **Node.js** (versi 18+) & **NPM**
- **MySQL** / **MariaDB**
- **Git**

---

## 🛠️ Panduan Instalasi (Git Clone)

Ikuti langkah-langkah berikut untuk mengunduh dan menjalankan proyek ini di komputer lokal:

### 1. Clone Repository
Buka terminal / Git Bash, lalu jalankan perintah:
```bash
git clone https://github.com/nabilroyyan/PERMINTAAN-SURAT-SAOBI.git
```

### 2. Masuk ke Folder Proyek
```bash
cd PERMINTAAN-SURAT-SAOBI/desa-saobi
```

### 3. Install Dependensi PHP (Composer)
```bash
composer install
```

### 4. Install Dependensi JavaScript (NPM)
```bash
npm install
```

### 5. Konfigurasi File Environment (`.env`)
Salin file contoh konfigurasi `.env.example` menjadi `.env`:

- **Windows (Command Prompt / PowerShell):**
  ```cmd
  copy .env.example .env
  ```
- **Linux / macOS / Git Bash:**
  ```bash
  cp .env.example .env
  ```

Buka file `.env` dengan text editor, lalu sesuaikan koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desa_saobi
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database dengan nama `desa_saobi` sudah dibuat di MySQL / phpMyAdmin).*

### 6. Generate Application Key
```bash
php artisan key:generate
```

### 7. Jalankan Migrasi Database & Seeder
Perintah ini akan membuat semua tabel dan mengisi data awal (akun admin, data contoh warga, pengaturan desa, dan 6 jenis surat standar):
```bash
php artisan migrate --seed
```

### 8. Hubungkan Storage Publik (Untuk PDF & Foto TTD)
```bash
php artisan storage:link
```

### 9. Compile Asset Frontend
Jalankan salah satu perintah berikut:
- **Untuk Mode Pengembangan (Hot Reload):**
  ```bash
  npm run dev
  ```
- **Atau untuk Mode Produksi (Build Asset Sekali Saja):**
  ```bash
  npm run build
  ```

### 10. Jalankan Server Lokal Laravel
Buka terminal baru di folder proyek, lalu jalankan:
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Login Bawaan (Default Credentials)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat langsung masuk dengan akun berikut:

| Peran (Role) | Email | Password | Keterangan |
|---|---|---|---|
| **Administrator** | `admin@saobi.desa.id` | `password` | Hak akses penuh panel admin, verifikasi surat, dan data penduduk |
| **Warga (Contoh)** | `warga@saobi.desa.id` | `password` | NIK: `3529012345670001` (Ahmad Warga Saobi) |

---

## 📂 Struktur Penting Proyek

```text
desa-saobi/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/             # Controller Admin (Permohonan, Penduduk, Jenis Surat, Pengaturan Desa)
│   │   └── Client/            # Controller Warga (Dashboard warga, permohonan surat)
│   └── Models/                # Eloquent Models (SuratRequest, SuratType, Penduduk, VillageSetting, dll.)
├── database/
│   ├── migrations/            # Struktur tabel database
│   └── seeders/               # Data awal database (DatabaseSeeder.php)
├── public/                    # Aset statis & template Excel kependudukan
├── resources/
│   └── views/
│       ├── admin/             # Tampilan blade panel administrator
│       ├── client/            # Tampilan blade portal warga
│       ├── layouts/           # Template layout (app.blade.php & navigation.blade.php)
│       └── pdf/               # Format template cetak PDF surat resmi
└── routes/
    ├── web.php                # Rute utama sistem
    └── auth.php               # Rute autentikasi Breeze
```

---

## 📄 Lisensi

Dikembangkan untuk kebutuhan pelayanan publik dan digitalisasi administrasi kependudukan **Desa Saobi**, Kecamatan Kangayan, Kabupaten Sumenep.
Open-source di bawah lisensi [MIT](LICENSE).
