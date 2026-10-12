<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total = SuratRequest::count();

        $pending = SuratRequest::where(
            'status', 'pending'
        )->count();

        $processing = SuratRequest::whereIn(
            'status', ['revision', 'processing']
        )->count();

        $completed = SuratRequest::where(
            'status', 'completed'
        )->count();

        $terbaru = SuratRequest::with([
            'user',
            'suratType',
        ])->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'total',
            'pending',
            'processing',
            'completed',
            'terbaru'
        ));
    }

    /**
     * Endpoint live-check untuk mendeteksi permohonan baru dari warga secara otomatis
     */
    public function liveCheck(Request $request)
    {
        $clientMaxCreated = (int) $request->query('max_created', 0);
        $clientTotal = (int) $request->query('total', 0);

        $latestCreated = SuratRequest::max('created_at');
        $serverMaxCreated = $latestCreated ? Carbon::parse($latestCreated)->timestamp : 0;
        $serverTotal = SuratRequest::count();

        $isChanged = ($serverMaxCreated > $clientMaxCreated) || ($serverTotal !== $clientTotal);

        if ($isChanged) {
            return response()->json([
                'changed' => true,
                'message' => 'Ada permohonan surat baru yang masuk dari warga! Memperbarui tampilan...',
            ]);
        }

        return response()->json(['changed' => false]);
    }
}
