<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;

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
}
