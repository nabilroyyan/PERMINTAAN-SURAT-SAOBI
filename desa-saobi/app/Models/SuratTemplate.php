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

    public function suratTypes()
    {
        return $this->hasMany(SuratType::class);
    }
}
