<?php

namespace App\Http\Controllers;

use App\Models\SuratRequest;
use App\Models\VillageSetting;

class SuratVerificationController extends Controller
{
    /**
     * Halaman publik untuk memverifikasi keaslian surat melalui token pada QR code.
     */
    public function show(string $token)
    {
        $surat = SuratRequest::with(['suratType', 'user'])
            ->where('verification_token', $token)
            ->firstOrFail();

        $desa = VillageSetting::first();

        return view('verifikasi.surat', compact('surat', 'desa'));
    }
}
