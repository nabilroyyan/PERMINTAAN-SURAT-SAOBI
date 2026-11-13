<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratRequest extends Model
{
    protected $fillable = [
        'user_id',
        'surat_type_id',
        'data_pemohon',
        'status',
        'nomor_surat',
        'tanggal_surat',
        'catatan_admin',
        'file_surat',
        'verification_token',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'data_pemohon' => 'array',
            'tanggal_surat' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SuratRequest $surat) {
            if (blank($surat->verification_token)) {
                $surat->verification_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Tautan halaman verifikasi keaslian surat.
     */
    public function verificationUrl(): ?string
    {
        if (blank($this->verification_token)) {
            return null;
        }

        return route('surat.verifikasi', $this->verification_token);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function suratType()
    {
        return $this->belongsTo(SuratType::class);
    }

    public function documents()
    {
        return $this->hasMany(SuratDocument::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class);
    }
}
