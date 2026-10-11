<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VillageSetting extends Model
{
    protected $fillable = [
        'nama_desa',
        'nama_kepala_desa',
        'nip_kepala_desa',
        'gambar_tanda_tangan',
        'alamat',
    ];
}
