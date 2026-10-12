<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * AJAX endpoint: Cek keberadaan NIK di master data penduduk Desa Saobi
     */
    public function checkNik(Request $request): JsonResponse
    {
        $request->validate([
            'nik' => ['required', 'string', 'digits:16'],
        ], [
            'nik.required' => 'Nomor NIK wajib diisi.',
            'nik.digits' => 'Nomor NIK harus berjumlah 16 digit angka.',
        ]);

        $nik = trim((string) $request->nik);
        $penduduk = Penduduk::where('nik', $nik)->first();

        if (!$penduduk) {
            return response()->json([
                'success' => false,
                'message' => 'NIK (' . $nik . ') tidak terdaftar dalam data penduduk Desa Saobi. Layanan ini dikhususkan untuk warga Desa Saobi.',
            ], 404);
        }

        // Cek apakah warga ini sudah memiliki akun pengguna
        $user = User::where('nik', $nik)->first() ?? $penduduk->user;

        return response()->json([
            'success' => true,
            'has_account' => !is_null($user),
            'nama' => $penduduk->nama_lengkap,
            'dusun' => $penduduk->dusun ? 'Dusun ' . $penduduk->dusun : 'Desa Saobi',
            'email' => $user?->email,
            'message' => $user
                ? 'NIK Terverifikasi! Selamat datang, ' . $penduduk->nama_lengkap . '.'
                : 'NIK terdaftar atas nama ' . $penduduk->nama_lengkap . ', namun akun login belum diaktifkan oleh Admin.',
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        if ($user->canAccessAdminPanel()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('client.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
