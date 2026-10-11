<?php

namespace Database\Seeders;

use App\Models\SuratType;
use App\Models\User;
use App\Models\VillageSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Desa Saobi
        User::updateOrCreate(
            ['email' => 'admin@saobi.desa.id'],
            [
                'name' => 'Administrator Desa Saobi',
                'password' => Hash::make('password'),
                'password_plain' => 'password',
                'role' => 'admin',
                'nik' => '3529000000000001',
            ]
        );

        // 2. Akun Warga Contoh
        $wargaUser = User::updateOrCreate(
            ['email' => 'warga@saobi.desa.id'],
            [
                'name' => 'Ahmad Warga Saobi',
                'password' => Hash::make('password'),
                'password_plain' => 'password',
                'role' => 'client',
                'nik' => '3529012345670001',
            ]
        );

        // 2.1 Master Data Penduduk Contoh
        \App\Models\Penduduk::updateOrCreate(
            ['nik' => '3529012345670001'],
            [
                'no_kk' => '3529012345670000',
                'nama_lengkap' => 'Ahmad Warga Saobi',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1995-04-12',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Dusun Saobi Timur RT 01 / RW 02',
                'dusun' => 'Saobi Timur',
                'rt' => '01',
                'rw' => '02',
                'agama' => 'Islam',
                'pekerjaan' => 'Nelayan',
                'status_perkawinan' => 'Kawin',
                'telepon' => '081234567890',
                'user_id' => $wargaUser->id,
            ]
        );

        \App\Models\Penduduk::updateOrCreate(
            ['nik' => '3529012345670002'],
            [
                'no_kk' => '3529012345670000',
                'nama_lengkap' => 'Siti Aminah',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1998-08-20',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Dusun Saobi Timur RT 01 / RW 02',
                'dusun' => 'Saobi Timur',
                'rt' => '01',
                'rw' => '02',
                'agama' => 'Islam',
                'pekerjaan' => 'Mengurus Rumah Tangga',
                'status_perkawinan' => 'Kawin',
                'telepon' => '081234567891',
                'user_id' => null,
            ]
        );

        \App\Models\Penduduk::updateOrCreate(
            ['nik' => '3529012345670003'],
            [
                'no_kk' => '3529012345679999',
                'nama_lengkap' => 'Bambang Wijaya',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '2001-01-15',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Dusun Saobi Barat RT 03 / RW 01',
                'dusun' => 'Saobi Barat',
                'rt' => '03',
                'rw' => '01',
                'agama' => 'Islam',
                'pekerjaan' => 'Wiraswasta',
                'status_perkawinan' => 'Belum Kawin',
                'telepon' => '081298765432',
                'user_id' => null,
            ]
        );

        // 3. Pengaturan Profil Desa Saobi
        VillageSetting::firstOrCreate(
            ['id' => 1],
            [
                'nama_desa' => 'Desa Saobi',
                'nama_kepala_desa' => 'MOH. SALEH, S.Pd',
                'nip_kepala_desa' => '198205122010011005',
                'alamat' => 'Jl. Pelabuhan Saobi, Pulau Saobi, Kec. Kangayan, Kab. Sumenep, Jawa Timur',
            ]
        );

        // 4. Data Jenis Surat
        $jenisSurats = [
            [
                'nama' => 'Surat Keterangan Usaha (SKU)',
                'kode' => 'SKU',
                'deskripsi' => 'Surat keterangan untuk keperluan izin atau permohonan modal usaha warga.',
                'persyaratan' => ['KTP', 'KK', 'Keterangan Jenis Usaha'],
                'format_nomor' => '470/{NOMOR}/SKU/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Keterangan Tidak Mampu (SKTM)',
                'kode' => 'SKTM',
                'deskripsi' => 'Surat keterangan untuk keperluan beasiswa, keringanan biaya RS, atau bantuan sosial.',
                'persyaratan' => ['KTP', 'KK'],
                'format_nomor' => '470/{NOMOR}/SKTM/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Keterangan Domisili',
                'kode' => 'SKD',
                'deskripsi' => 'Surat keterangan bertempat tinggal resmi di wilayah Desa Saobi.',
                'persyaratan' => ['KTP', 'KK'],
                'format_nomor' => '470/{NOMOR}/SKD/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Pengantar SKCK',
                'kode' => 'SKCK',
                'deskripsi' => 'Surat pengantar desa untuk pengurusan SKCK di Polsek Kangayan.',
                'persyaratan' => ['KTP', 'KK', 'Pas Foto 4x6'],
                'format_nomor' => '300/{NOMOR}/SKCK/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Keterangan Belum Menikah',
                'kode' => 'SKBM',
                'deskripsi' => 'Surat keterangan status belum pernah menikah bagi warga.',
                'persyaratan' => ['KTP', 'KK'],
                'format_nomor' => '474/{NOMOR}/SKBM/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
            [
                'nama' => 'Surat Keterangan Kematian',
                'kode' => 'SKK',
                'deskripsi' => 'Surat keterangan pelaporan kematian warga di wilayah Desa Saobi.',
                'persyaratan' => ['KTP Almarhum', 'KK'],
                'format_nomor' => '472/{NOMOR}/SKK/435.312.108/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($jenisSurats as $jenis) {
            SuratType::updateOrCreate(
                ['kode' => $jenis['kode']],
                $jenis
            );
        }
    }
}
