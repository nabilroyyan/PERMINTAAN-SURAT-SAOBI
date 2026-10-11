<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SuratType extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'persyaratan',
        'format_nomor',
        'nomor_terakhir',
        'panjang_nomor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'persyaratan' => 'array',
            'is_active' => 'boolean',
            'nomor_terakhir' => 'integer',
            'panjang_nomor' => 'integer',
        ];
    }

    public function suratRequests()
    {
        return $this->hasMany(SuratRequest::class);
    }

    /**
     * Generate template nomor surat otomatis berikutnya
     */
    public function generateNextNomorSurat($date = null): string
    {
        $carbon = $date ? Carbon::parse($date) : now();
        $nextNum = ($this->nomor_terakhir ?? 0) + 1;
        $padded = str_pad((string) $nextNum, $this->panjang_nomor ?: 2, '0', STR_PAD_LEFT);

        $bulanRomawiList = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $bulanRomawi = $bulanRomawiList[(int) $carbon->format('n')] ?? 'I';

        $template = $this->format_nomor ?: '470/{NOMOR}/{KODE}/435.312.108/{TAHUN}';

        $replacements = [
            '{NOMOR}' => $padded,
            '{KODE}' => $this->kode ?: 'DS',
            '{TAHUN}' => $carbon->format('Y'),
            '{BULAN}' => $carbon->format('m'),
            '{BULAN_ROMAWI}' => $bulanRomawi,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Simpan nomor terakhir jika nomor yang digunakan lebih besar
     */
    public function recordUsedNumber(?string $nomorDigunakan = null): void
    {
        $this->increment('nomor_terakhir');
    }
}