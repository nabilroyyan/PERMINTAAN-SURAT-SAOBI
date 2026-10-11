<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
