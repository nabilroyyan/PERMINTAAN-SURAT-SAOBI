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
        // 1. Akun Admin Desa Saobi (Administrator juga warga Desa Saobi)
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@saobi.desa.id'],
            [
                'name' => 'Administrator Desa Saobi',
                'password' => Hash::make('password'),
                'password_plain' => 'password',
                'role' => 'admin',
                'nik' => '3529000000000001',
            ]
        );

        \App\Models\Penduduk::updateOrCreate(
            ['nik' => '3529000000000001'],
            [
                'no_kk' => '3529000000000001',
                'nama_lengkap' => 'Administrator Desa Saobi',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Dusun Saobi Barat RT 01 / RW 01',
                'dusun' => 'Saobi Barat',
                'rt' => '01',
                'rw' => '01',
                'agama' => 'Islam',
                'pekerjaan' => 'Perangkat Desa',
                'status_perkawinan' => 'Kawin',
                'telepon' => '081200000001',
                'user_id' => $adminUser->id,
            ]
        );

        // 3. Akun Warga Contoh
        $wargaUser = User::updateOrCreate(
            ['email' => 'warga@saobi.desa.id'],
            [
                'name' => 'Ahmad Warga Saobi',
                'password' => Hash::make('password'),
                'password_plain' => 'password',
                'role' => 'warga',
                'nik' => '3529012345670001',
            ]
        );

        // 3.1 Master Data Penduduk Contoh
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
        VillageSetting::updateOrCreate(
            ['id' => 1],
            [
                'nama_desa' => 'Desa Saobi',
                'kecamatan' => 'Kangayan',
                'kabupaten' => 'Sumenep',
                'provinsi' => 'Jawa Timur',
                'nama_kepala_desa' => 'HOSAINI, SH',
                'nip_kepala_desa' => '-',
                'alamat' => 'Jalan Raya Masjid No. 50',
                'kode_pos' => '69491',
                'telepon' => null,
                'email' => 'desasaobi90@gmail.com',
                'website' => null,
            ]
        );

        // 4. Template Isi Surat Contoh
        $templateDomisili = \App\Models\SuratTemplate::updateOrCreate(
            ['nama' => 'Surat Keterangan Domisili'],
            [
                'deskripsi' => 'Template isi surat keterangan domisili standar Desa Saobi.',
                'konten' => implode("\n", [
                    'Yang bertanda tangan di bawah ini Kepala {nama_desa}, {wilayah_lengkap}, dengan ini menerangkan dengan sebenarnya bahwa:',
                    '',
                    '{tabel_pemohon}',
                    '',
                    'Adalah benar berdomisili dan bertempat tinggal di {alamat} dan berkeperluan {keperluan}.',
                    '',
                    'Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',
                ]),
                'is_active' => true,
            ]
        );

        // 5. Data Jenis Surat
        $jenisSurats = [
            [
                'nama' => 'Surat Keterangan Domisili',
                'kode' => 'SKD',
                'deskripsi' => 'Surat keterangan bertempat tinggal resmi di wilayah Desa Saobi.',
                'persyaratan' => ['KTP', 'KK'],
                'format_nomor' => '400.12.2/{NOMOR}/326.105/{TAHUN}',
                'nomor_terakhir' => 0,
                'panjang_nomor' => 2,
                'is_active' => true,
                'surat_template_id' => $templateDomisili->id,
                'form_fields' => [
                    [
                        'kode' => 'alamat',
                        'label' => 'Alamat Domisili',
                        'tipe' => 'textarea',
                        'wajib' => true,
                        'placeholder' => null,
                        'help' => 'Otomatis terisi dari data kependudukan. Sesuaikan jika perlu.',
                        'default_from' => 'alamat',
                        'options' => [],
                    ],
                    [
                        'kode' => 'keperluan',
                        'label' => 'Keperluan Surat',
                        'tipe' => 'textarea',
                        'wajib' => true,
                        'placeholder' => 'Contoh: Persyaratan melamar pekerjaan',
                        'help' => 'Jelaskan secara singkat tujuan dibuatnya surat.',
                        'default_from' => null,
                        'options' => [],
                    ],
                ],
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
