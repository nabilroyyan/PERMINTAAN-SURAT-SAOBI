<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccountActivationRequest;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AccountActivationController extends Controller
{
    /**
     * Simpan permohonan aktivasi akun dari warga (diproses admin/petugas desa).
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalisasi email ke huruf kecil agar aturan 'lowercase' tidak memunculkan pesan asing
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $validated = $request->validateWithBag('aktivasi', [
            'nik' => ['required', 'digits:16'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nik.required' => 'Nomor NIK wajib diisi.',
            'nik.digits' => 'Nomor NIK harus terdiri dari 16 digit angka.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.lowercase' => 'Alamat email tidak boleh menggunakan huruf besar.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $penduduk = Penduduk::where('nik', $validated['nik'])->first();

        if (!$penduduk) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK tidak terdaftar dalam data penduduk Desa Saobi. Silakan lapor ke Balai Desa.',
            ], 'aktivasi');
        }

        $sudahPunyaAkun = User::where('nik', $validated['nik'])->exists() || $penduduk->user_id;

        if ($sudahPunyaAkun) {
            return back()->withInput()->withErrors([
                'nik' => 'NIK ini sudah memiliki akun yang aktif. Silakan langsung masuk menggunakan akun Anda.',
            ], 'aktivasi');
        }

        AccountActivationRequest::updateOrCreate(
            ['nik' => $validated['nik'], 'status' => 'pending'],
            [
                'nama' => $penduduk->nama_lengkap,
                'email' => $validated['email'],
                'password_plain' => $validated['password'],
            ]
        );

        return back()->with(
            'activation_status',
            'Permintaan aktivasi akun Anda sedang diproses. Mohon tunggu petugas desa mengaktifkan akun Anda, lalu masuk kembali menggunakan email dan kata sandi yang Anda ajukan.'
        );
    }
}
