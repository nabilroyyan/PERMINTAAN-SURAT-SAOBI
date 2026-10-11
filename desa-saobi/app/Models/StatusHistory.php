<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $fillable = [
        'surat_request_id',
        'user_id',
        'status',
        'catatan',
    ];

    public function suratRequest()
    {
        return $this->belongsTo(SuratRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
