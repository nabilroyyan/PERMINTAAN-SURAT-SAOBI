<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageSetting extends Model
{
    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'gambar_tanda_tangan',
        'alamat',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'logo_kiri',
        'logo_kanan',
    ];

    /**
     * Rangkaian wilayah lengkap desa, contoh: "Kecamatan Kangayan, Kabupaten Sumenep".
     */
    public function wilayahLengkap(): string
    {
        $parts = [];

        if (filled($this->kecamatan)) {
            $parts[] = 'Kecamatan ' . $this->kecamatan;
        }

        if (filled($this->kabupaten)) {
            $parts[] = 'Kabupaten ' . $this->kabupaten;
        }

        if (filled($this->provinsi)) {
            $parts[] = 'Provinsi ' . $this->provinsi;
        }

        return implode(', ', $parts);
    }

    /**
     * Ubah gambar logo menjadi data URI base64 (aman untuk dompdf).
     */
    public function logoKiriDataUri(): ?string
    {
        return $this->imageDataUri($this->logo_kiri);
    }

    public function logoKananDataUri(): ?string
    {
        return $this->imageDataUri($this->logo_kanan);
    }

    public function tandaTanganDataUri(): ?string
    {
        return $this->imageDataUri($this->gambar_tanda_tangan);
    }

    private function imageDataUri(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $fullPath = public_path('storage/' . $path);

        if (!file_exists($fullPath)) {
            return null;
        }

        $mime = mime_content_type($fullPath) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
    }
}
