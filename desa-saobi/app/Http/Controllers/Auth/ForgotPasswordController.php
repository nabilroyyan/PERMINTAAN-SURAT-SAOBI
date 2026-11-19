<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman pengajuan lupa kata sandi.
     */
    public function show(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Simpan pengajuan reset kata sandi (beserta kata sandi baru yang diinginkan warga).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'digits:16'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nik.required' => 'Nomor NIK wajib diisi.',
            'nik.digits' => 'Nomor NIK harus terdiri dari 16 digit angka.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::where('nik', $validated['nik'])->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK tidak ditemukan atau akun belum dibuat. Silakan lapor ke Balai Desa Saobi.',
            ]);
        }

        PasswordResetRequest::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            [
                'nik' => $user->nik,
                'email' => $user->email,
                'nama' => $user->name,
                'password_plain' => $validated['password'],
            ]
        );

        return back()->with(
            'status',
            'Permintaan Anda sedang diproses. Mohon tunggu petugas desa menyetujui pengajuan kata sandi Anda, lalu masuk kembali menggunakan kata sandi tersebut.'
        );
    }
}
