<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountActivationRequest extends Model
{
    protected $fillable = [
        'nik',
        'nama',
        'email',
        'password_plain',
        'status',
        'catatan',
        'handled_by',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
