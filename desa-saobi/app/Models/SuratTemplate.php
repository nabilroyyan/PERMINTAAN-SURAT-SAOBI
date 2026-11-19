<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTemplate extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'konten',
        'variabel_tambahan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variabel_tambahan' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Field isian warga yang didefinisikan pada template ini.
     * Inilah isi surat yang harus diinput oleh warga.
     */
    public function formFields(): array
    {
        $fields = $this->variabel_tambahan;

        return is_array($fields) ? $fields : [];
    }

    public function suratTypes()
    {
        return $this->hasMany(SuratType::class);
    }
}
