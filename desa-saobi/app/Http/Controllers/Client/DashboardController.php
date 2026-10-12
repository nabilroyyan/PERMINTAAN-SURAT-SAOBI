<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratRequest::where(
            'user_id',
            $request->user()->id
        );

        $total = (clone $query)->count();

        $pending = (clone $query)
            ->where('status', 'pending')
            ->count();

        $processing = (clone $query)
            ->whereIn('status', ['revision', 'processing'])
            ->count();

        $completed = (clone $query)
            ->where('status', 'completed')
            ->count();

        $suratTerbaru = (clone $query)
            ->with('suratType')
            ->latest()
            ->take(5)
            ->get();

        return view('client.dashboard', compact(
            'total',
            'pending',
            'processing',
            'completed',
            'suratTerbaru'
        ));
    }

    /**
     * Endpoint live-check ringan untuk mendeteksi perubahan status atau terbitnya surat
     */
    public function liveCheck(Request $request)
    {
        $user = $request->user();
        $context = $request->query('context', 'list');

        if ($context === 'show') {
            $id = $request->query('id');
            $currentStatus = $request->query('status');
            $currentUpdated = (int) $request->query('updated', 0);
            $currentHasFile = filter_var($request->query('has_file', false), FILTER_VALIDATE_BOOLEAN);

            $surat = SuratRequest::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$surat) {
                return response()->json(['changed' => false]);
            }

            $suratUpdated = $surat->updated_at ? $surat->updated_at->timestamp : 0;
            $suratHasFile = !empty($surat->file_surat);

            $isChanged = ($surat->status !== $currentStatus)
                || ($suratUpdated > $currentUpdated)
                || ($suratHasFile !== $currentHasFile);

            if ($isChanged) {
                $statusLabels = [
                    'pending' => 'Menunggu Verifikasi',
                    'processing' => 'Sedang Diproses',
                    'revision' => 'Perlu Perbaikan / Revisi',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai & Diterbitkan',
                ];

                $statusLabel = $statusLabels[$surat->status] ?? ucfirst($surat->status);

                $message = $surat->status === 'completed'
                    ? 'Surat resmi telah diterbitkan oleh Admin! Memperbarui halaman...'
                    : 'Status permohonan telah diperbarui menjadi: ' . $statusLabel;

                return response()->json([
                    'changed' => true,
                    'status' => $surat->status,
                    'status_label' => $statusLabel,
                    'has_file' => $suratHasFile,
                    'message' => $message,
                ]);
            }

            return response()->json(['changed' => false]);
        }

        // Context: 'list' atau 'dashboard'
        $clientMaxUpdated = (int) $request->query('max_updated', 0);
        $clientTotal = (int) $request->query('total', 0);

        $latestUpdated = SuratRequest::where('user_id', $user->id)->max('updated_at');
        $serverMaxUpdated = $latestUpdated ? Carbon::parse($latestUpdated)->timestamp : 0;
        $serverTotal = SuratRequest::where('user_id', $user->id)->count();

        $isChanged = ($serverMaxUpdated > $clientMaxUpdated) || ($serverTotal !== $clientTotal);

        if ($isChanged) {
            return response()->json([
                'changed' => true,
                'message' => 'Ada pembaruan status permohonan surat dari Admin! Memperbarui halaman...',
            ]);
        }

        return response()->json(['changed' => false]);
    }
}
