<?php

namespace App\Support;

use App\Models\SuratRequest;
use App\Models\VillageSetting;
use Carbon\Carbon;

class SuratTemplateRenderer
{
    /**
     * Daftar variabel yang tersedia, dikelompokkan untuk tampilan editor.
     */
    public static function availableVariables(): array
    {
        return [
            'Data Pemohon' => [
                'nama' => 'Nama lengkap pemohon',
                'nik' => 'Nomor Induk Kependudukan (NIK)',
                'tempat_lahir' => 'Tempat lahir',
                'tanggal_lahir' => 'Tanggal lahir (contoh: 12 April 1995)',
                'jenis_kelamin' => 'Jenis kelamin',
                'status_perkawinan' => 'Status perkawinan',
                'agama' => 'Agama',
                'pekerjaan' => 'Pekerjaan',
                'dusun' => 'Dusun',
                'rt' => 'RT',
                'rw' => 'RW',
                'alamat' => 'Alamat lengkap',
                'keperluan' => 'Keperluan surat',
                'umur' => 'Umur (tahun)',
            ],
            'Data Surat' => [
                'nama_surat' => 'Nama / judul jenis surat',
                'nomor_surat' => 'Nomor surat',
                'tanggal_surat' => 'Tanggal surat (contoh: 13 November 2026)',
                'kode_surat' => 'Kode jenis surat',
            ],
            'Data Desa' => [
                'nama_desa' => 'Nama desa',
                'nama_kepala_desa' => 'Nama kepala desa',
                'nip_kepala_desa' => 'NIP kepala desa',
                'alamat_desa' => 'Alamat kantor desa',
                'wilayah_lengkap' => 'Wilayah lengkap (Kecamatan, Kabupaten, Provinsi)',
            ],
            'Data Wilayah' => [
                'kecamatan' => 'Kecamatan',
                'kabupaten' => 'Kabupaten',
                'provinsi' => 'Provinsi',
                'kode_pos' => 'Kode pos',
                'telepon' => 'Telepon desa',
                'email' => 'Email desa',
            ],
            'Blok Siap Pakai' => [
                'tabel_pemohon' => 'Tabel data pemohon (Nama, NIK, Alamat, dst.)',
            ],
        ];
    }

    /**
     * Susun nilai untuk seluruh variabel template.
     */
    public static function variables(
        SuratRequest $surat,
        ?VillageSetting $desa = null,
        ?string $nomorSurat = null,
        ?Carbon $tanggalSurat = null
    ): array {
        $penduduk = $surat->user?->penduduk;
        $data = $surat->data_pemohon ?? [];

        $tanggalLahirRaw = $data['tanggal_lahir'] ?? $penduduk?->tanggal_lahir;
        $tanggalLahir = self::formatTanggal($tanggalLahirRaw, 'd F Y');

        $umur = '-';
        if (filled($tanggalLahirRaw)) {
            try {
                $umur = (string) Carbon::parse($tanggalLahirRaw)->age;
            } catch (\Throwable $e) {
                $umur = '-';
            }
        }

        $dusun = $data['dusun'] ?? $penduduk?->dusun;
        $rt = $data['rt'] ?? $penduduk?->rt;
        $rw = $data['rw'] ?? $penduduk?->rw;

        $alamat = $data['alamat'] ?? '';
        if (blank($alamat)) {
            $alamat = self::composeAlamat($dusun, $rt, $rw, $desa);
        }

        $tanggalSurat = $tanggalSurat ? $tanggalSurat->copy() : now();

        $variables = [
            'nama' => $data['nama'] ?? $penduduk?->nama_lengkap ?? $surat->user?->name ?? '-',
            'nik' => $data['nik'] ?? $penduduk?->nik ?? $surat->user?->nik ?? '-',
            'tempat_lahir' => $data['tempat_lahir'] ?? $penduduk?->tempat_lahir ?? '-',
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? $penduduk?->jenis_kelamin ?? '-',
            'status_perkawinan' => $data['status_perkawinan'] ?? $penduduk?->status_perkawinan ?? '-',
            'agama' => $data['agama'] ?? $penduduk?->agama ?? '-',
            'pekerjaan' => $data['pekerjaan'] ?? $penduduk?->pekerjaan ?? '-',
            'dusun' => $dusun ?: '-',
            'rt' => $rt ?: '-',
            'rw' => $rw ?: '-',
            'alamat' => $alamat ?: '-',
            'keperluan' => $data['keperluan'] ?? '-',
            'umur' => $umur,

            'nama_surat' => $surat->suratType?->nama ?? 'Surat Keterangan',
            'nomor_surat' => $nomorSurat ?: ($surat->nomor_surat ?? '-'),
            'tanggal_surat' => $tanggalSurat->locale('id')->translatedFormat('d F Y'),
            'kode_surat' => $surat->suratType?->kode ?? '-',

            'nama_desa' => $desa?->nama_desa ?? 'Desa Saobi',
            'nama_kepala_desa' => $desa?->nama_kepala_desa ?? '-',
            'nip_kepala_desa' => $desa?->nip_kepala_desa ?? '-',
            'alamat_desa' => $desa?->alamat ?? '-',
            'wilayah_lengkap' => $desa?->wilayahLengkap() ?: '-',
            'kecamatan' => $desa?->kecamatan ?: '-',
            'kabupaten' => $desa?->kabupaten ?: '-',
            'provinsi' => $desa?->provinsi ?: '-',
            'kode_pos' => $desa?->kode_pos ?: '-',
            'telepon' => $desa?->telepon ?: '-',
            'email' => $desa?->email ?: '-',
        ];

        // Gabungkan variabel tambahan yang diisi pemohon (mis. batas tanah sebelah barat/timur)
        $tambahan = $data['tambahan'] ?? [];
        if (is_array($tambahan)) {
            foreach ($tambahan as $key => $value) {
                if (is_string($key) && $key !== '' && !array_key_exists($key, $variables)) {
                    if (is_array($value)) {
                        $value = implode(', ', array_filter(array_map(fn ($v) => (string) $v, $value), fn ($v) => $v !== ''));
                    }
                    $variables[$key] = (string) ($value ?? '-');
                }
            }
        }

        return $variables;
    }

    /**
     * Nama variabel bawaan yang tidak boleh dipakai ulang oleh variabel tambahan.
     */
    public static function reservedKeys(): array
    {
        $keys = [];

        foreach (self::availableVariables() as $group) {
            $keys = array_merge($keys, array_keys($group));
        }

        $keys[] = 'tabel_pemohon';
        $keys[] = 'tambahan';

        return array_values(array_unique($keys));
    }

    /**
     * Kode yang tidak boleh dipakai sebagai field isian pemohon.
     * "alamat" dan "keperluan" dikecualikan agar tetap dapat diatur admin per jenis surat.
     */
    public static function formFieldReservedKeys(): array
    {
        $allowed = ['alamat', 'keperluan'];

        return array_values(array_diff(self::reservedKeys(), $allowed));
    }

    /**
     * Ubah isi template menjadi HTML siap cetak dengan mengisi variabel.
     */
    public static function render(?string $konten, array $variables): string
    {
        if (blank($konten)) {
            return '';
        }

        $tabelToken = '@@TABEL_PEMOHON@@';

        // Ganti blok khusus dengan token agar tidak ikut diproses nl2br
        $text = str_replace('{tabel_pemohon}', $tabelToken, $konten);

        // Baris baru menjadi <br> sebelum blok HTML disisipkan
        $text = nl2br($text);

        // Ganti seluruh variabel {tag} dengan nilai yang sudah di-escape
        $replacements = [];
        foreach ($variables as $key => $value) {
            $replacements['{' . $key . '}'] = e((string) $value);
        }
        $text = str_replace(array_keys($replacements), array_values($replacements), $text);

        // Sisipkan blok tabel data pemohon
        return str_replace($tabelToken, self::tablePemohon($variables), $text);
    }

    /**
     * Tabel data pemohon standar yang bisa disisipkan lewat {tabel_pemohon}.
     */
    public static function tablePemohon(array $v): string
    {
        $ttl = trim(($v['tempat_lahir'] ?? '-') . (filled($v['tanggal_lahir'] ?? null) && $v['tanggal_lahir'] !== '-' ? ', ' . $v['tanggal_lahir'] : ''));

        $rows = [
            'Nama' => $v['nama'] ?? '-',
            'NIK' => $v['nik'] ?? '-',
            'Tempat / Tanggal Lahir' => $ttl,
            'Jenis Kelamin' => $v['jenis_kelamin'] ?? '-',
            'Status Perkawinan' => $v['status_perkawinan'] ?? '-',
            'Agama' => $v['agama'] ?? '-',
            'Pekerjaan' => $v['pekerjaan'] ?? '-',
            'Alamat' => $v['alamat'] ?? '-',
        ];

        $html = '<table class="table-pemohon">';
        foreach ($rows as $label => $value) {
            $html .= '<tr><td class="label">' . e($label) . '</td>'
                . '<td class="colon">:</td>'
                . '<td>' . e((string) $value) . '</td></tr>';
        }
        $html .= '</table>';

        return $html;
    }

    private static function composeAlamat($dusun, $rt, $rw, ?VillageSetting $desa): string
    {
        $parts = [];

        if (filled($dusun)) {
            $rtRw = '';
            if (filled($rt) || filled($rw)) {
                $rtRw = ' RT ' . ($rt ?: '-') . ' / RW ' . ($rw ?: '-');
            }
            $parts[] = 'Dusun ' . $dusun . $rtRw;
        }

        if (filled($desa?->nama_desa)) {
            $parts[] = $desa->nama_desa;
        }

        if (filled($wilayah = $desa?->wilayahLengkap())) {
            $parts[] = $wilayah;
        }

        if (empty($parts)) {
            return $desa?->alamat ?? '-';
        }

        return implode(', ', $parts);
    }

    private static function formatTanggal($value, string $format): string
    {
        if (blank($value)) {
            return '-';
        }

        try {
            return Carbon::parse($value)->locale('id')->translatedFormat($format);
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}
