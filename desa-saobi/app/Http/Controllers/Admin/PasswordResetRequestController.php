<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordResetRequestController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        if (!in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $requests = PasswordResetRequest::with(['user', 'handler'])
            ->where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = PasswordResetRequest::where('status', 'pending')->count();

        return view('admin.reset_sandi.index', compact('requests', 'status', 'pendingCount'));
    }

    /**
     * Setujui pengajuan. Kata sandi mengikuti ajuan warga, kecuali admin mengisi kata sandi lain.
     */
    public function approve(Request $request, PasswordResetRequest $resetRequest)
    {
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $user = $resetRequest->user;

        if (!$user) {
            return back()->with('error', 'Akun pengguna tidak ditemukan.');
        }

        // Pakai kata sandi ajuan warga; admin boleh menimpanya secara manual.
        $newPassword = $request->filled('password')
            ? $validated['password']
            : $resetRequest->password_plain;

        if (blank($newPassword)) {
            return back()->with('error', 'Permintaan ini tidak memuat kata sandi ajuan. Isi kata sandi baru secara manual terlebih dahulu.');
        }

        $user->update([
            'password' => Hash::make($newPassword),
            'password_plain' => $newPassword,
        ]);

        $resetRequest->update([
            'status' => 'approved',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
            'catatan' => 'Kata sandi direset sesuai ajuan warga oleh ' . $request->user()->name,
        ]);

        return back()->with('success', 'Kata sandi untuk ' . $user->name . ' berhasil direset sesuai ajuan warga.');
    }

    /**
     * Tolak pengajuan reset kata sandi.
     */
    public function reject(Request $request, PasswordResetRequest $resetRequest)
    {
        if ($resetRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $resetRequest->update([
            'status' => 'rejected',
            'handled_by' => $request->user()->id,
            'handled_at' => now(),
            'catatan' => $validated['catatan'],
        ]);

        return back()->with('success', 'Pengajuan reset kata sandi berhasil ditolak.');
    }
}
