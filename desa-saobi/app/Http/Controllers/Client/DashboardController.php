<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
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
}
