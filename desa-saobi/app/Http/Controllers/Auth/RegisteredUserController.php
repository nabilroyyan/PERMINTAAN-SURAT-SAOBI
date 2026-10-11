<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Cek data NIK & KK secara real-time via AJAX untuk auto-fill
     */
    public function checkNikKk(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => ['required', 'digits:16'],
            'no_kk' => ['required', 'digits:16'],
        ]);

        $penduduk = Penduduk::where('nik', $request->nik)
            ->where('no_kk', $request->no_kk)
            ->first();

        if (!$penduduk) {
            return response()->json([
                'success' => false,
                'message' => 'Data NIK dan Nomor KK tidak cocok atau belum terdaftar di data kependudukan Desa Saobi. Silakan hubungi perangkat desa.',
            ], 404);
        }

        // Cek apakah NIK ini sudah punya akun di website
        $existingUser = User::where('nik', $request->nik)->first();
        if ($existingUser || $penduduk->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'NIK ini sudah memiliki akun terdaftar dengan email ' . ($existingUser?->email ?? 'yang sudah ada') . '. Silakan langsung login.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kependudukan ditemukan! Formulir telah otomatis terisi.',
            'data' => [
                'nama' => $penduduk->nama_lengkap,
                'tempat_lahir' => $penduduk->tempat_lahir ?? '-',
                'tanggal_lahir' => $penduduk->tanggal_lahir ? $penduduk->tanggal_lahir->translatedFormat('d F Y') : '-',
                'jenis_kelamin' => $penduduk->jenis_kelamin,
                'alamat' => $penduduk->alamat ?? ($penduduk->dusun ? 'Dusun ' . $penduduk->dusun : 'Desa Saobi'),
                'dusun' => $penduduk->dusun ?? '-',
                'rt' => $penduduk->rt ?? '-',
                'rw' => $penduduk->rw ?? '-',
                'pekerjaan' => $penduduk->pekerjaan ?? '-',
            ],
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:users,nik'],
            'no_kk' => ['required', 'digits:16'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [ 
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah 16 digit angka.',
            'nik.unique' => 'NIK ini sudah terdaftar sebagai akun.',
            'no_kk.required' => 'Nomor KK wajib diisi.',
            'no_kk.digits' => 'Nomor KK harus berjumlah 16 digit angka.',
        ]);

        // Verifikasi keabsahan NIK dan KK di tabel penduduks
        $penduduk = Penduduk::where('nik', $validated['nik'])
            ->where('no_kk', $validated['no_kk'])
            ->first();

        if (!$penduduk) {
            return back()->withInput()->withErrors([
                'nik' => 'Data NIK dan Nomor KK tidak terdaftar di data kependudukan Desa Saobi. Silakan hubungi perangkat desa.',
            ]);
        }

        // Simpan Akun User Warga
        $user = User::create([
            'name' => $penduduk->nama_lengkap, // Gunakan nama resmi dari master data
            'nik' => $validated['nik'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
        ]);

        // Hubungkan data penduduk dengan user_id
        $penduduk->update(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('client.dashboard')
            ->with('success', 'Selamat datang! Akun Anda berhasil terdaftar dan terhubung dengan data kependudukan Desa Saobi.');
    }
}