<?php

use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Client\SuratRequestController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SuratManagementController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\SuratTypeController;
use App\Http\Controllers\Admin\VillageSettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->canAccessAdminPanel()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->canAccessAdminPanel()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('client.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:client'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/surat/create', [SuratRequestController::class, 'create'])
            ->name('surat.create');

        Route::post('/surat', [SuratRequestController::class, 'store'])
            ->name('surat.store');

        Route::get('/surat', [SuratRequestController::class, 'index'])
            ->name('surat.index');

        Route::get('/surat/{suratRequest}', [SuratRequestController::class, 'show'])
            ->name('surat.show');

        Route::get('/surat/{suratRequest}/download', [SuratRequestController::class, 'download'])
            ->name('surat.download');

        Route::get('/live-check', [ClientDashboard::class, 'liveCheck'])
            ->name('live-check');
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/live-check', [AdminDashboard::class, 'liveCheck'])
            ->name('live-check');

        Route::get('/permohonan', [SuratManagementController::class, 'index'])
            ->name('permohonan.index');

        Route::get('/permohonan/{suratRequest}', [SuratManagementController::class, 'show'])
            ->name('permohonan.show');

        Route::patch('/permohonan/{suratRequest}/status', [SuratManagementController::class, 'updateStatus'])
            ->name('permohonan.status');

        Route::post('/permohonan/{suratRequest}/terbitkan', [SuratManagementController::class, 'publish'])
            ->name('permohonan.publish');

        Route::get('/penduduk', [PendudukController::class, 'index'])
            ->name('penduduk.index');

        Route::get('/penduduk/create', [PendudukController::class, 'create'])
            ->name('penduduk.create');

        Route::post('/penduduk', [PendudukController::class, 'store'])
            ->name('penduduk.store');

        Route::post('/penduduk/import', [PendudukController::class, 'import'])
            ->name('penduduk.import');

        Route::get('/penduduk/template', [PendudukController::class, 'downloadTemplate'])
            ->name('penduduk.template');

        Route::get('/penduduk/sample-5000', [PendudukController::class, 'downloadSample5000'])
            ->name('penduduk.sample-5000');

        Route::delete('/penduduk/{penduduk}', [PendudukController::class, 'destroy'])
            ->name('penduduk.destroy');

        Route::put('/penduduk/{penduduk}/account', [PendudukController::class, 'updateAccount'])
            ->name('penduduk.account');

        Route::resource('jenis-surat', SuratTypeController::class);

        Route::get('/pengaturan', [VillageSettingController::class, 'index'])
            ->name('settings.index');

        Route::post('/pengaturan', [VillageSettingController::class, 'update'])
            ->name('settings.update');
    });

require __DIR__.'/auth.php';