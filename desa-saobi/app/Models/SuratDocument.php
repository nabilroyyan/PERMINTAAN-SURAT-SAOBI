<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDocument extends Model
{
    protected $fillable = [
        'surat_request_id',
        'nama_file',
        'path_file',
        'jenis_dokumen',
    ];

    public function suratRequest()
    {
        return $this->belongsTo(SuratRequest::class);
    }
}
