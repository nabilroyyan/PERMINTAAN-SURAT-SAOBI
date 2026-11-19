<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountActivationRequest;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountActivationRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $requests = AccountActivationRequest::with('handler')
            ->where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = AccountActivationRequest::where('status', 'pending')->count();

        return view('admin.aktivasi_akun.index', compact('requests', 'status', 'pendingCount'));
    }

    /**
     * Setujui permohonan & aktifkan akun warga.
     */
    public function approve(Request $request, AccountActivationRequest $activationRequest)
    {
        if ($activationRequest->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $penduduk = Penduduk::where('nik', $activationRequest->nik)->first();

        if (!$penduduk) {
            return back()->with('error', 'Data penduduk untuk NIK ini tidak ditemukan.');
        }

        $sudahPunyaAkun = User::where('nik', $activationRequest->nik)->exists() || $penduduk->user_id;
        if ($sudahPunyaAkun) {
            $activationRequest->update([
                'status' => 'approved',
                'handled_by' => $request->user()->id,
                'handled_at' => now(),
                'catatan' => 'Akun sudah aktif sebelumnya.',
            ]);

            return back()->with('error', 'Akun untuk NIK ini ternyata sudah aktif.');
        }

        if (User::where('email', $activationRequest->email)->exists()) {
            return back()->with('error', 'Alamat email yang diajukan sudah dipakai akun lain. Tolak atau minta warga mengajukan ulang.');
        }

        DB::transaction(function () use ($request, $activationRequest, $penduduk) {
            $user = User::create([
                'name' => $penduduk->nama_lengkap,
                'nik' => $penduduk->nik,
                'email' => strtolower($activationRequest->email),
                'password' => Hash::make($activationRequest->password_plain),
                'password_plain' => $activationRequest->password_plain,
                'role' => 'warga',
            ]);

            $penduduk->update(['user_id' => $user->id]);

            $activationRequest->update([
                'status' => 'approved',
                'handled_by' => $request->user()->id,
                'handled_at' => now(),
                'catatan' => 'Akun diaktifkan oleh ' . $request->user()->name,
            ]);
        });

        return back()->with('success', 'Akun untuk ' . $penduduk->nama_lengkap . ' berhasil diaktifkan.');
    }

    /**
     * Tolak permohonan aktivasi akun.
     */
    public function reject(Request $request, AccountActivationRequest $activationRequest)
    {
        if ($activationRequest->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $activationRequest->update([
            'status' => 'rejected',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
            'catatan' => $validated['catatan'],
        ]);

        return back()->with('success', 'Permohonan aktivasi akun berhasil ditolak.');
    }
}
