<?php

use Illuminate\Support\Facades\Route;
use Spinotek\TaskMonitoring\Http\Controllers\TaskMonitoringController;

Route::middleware('web')->prefix('spinotek/ticket')->name('spinotek.ticket.')->group(function () {
    Route::get('/', [TaskMonitoringController::class, 'dashboard'])->name('dashboard');
    Route::get('tasks', [TaskMonitoringController::class, 'dashboard'])->name('tasks.index');
    Route::get('version-logs', [TaskMonitoringController::class, 'dashboard'])->name('version-logs.index');

    Route::get('assets/logo.png', function () {
        $path = __DIR__ . '/../resources/assets/spinotek-logo.png';
        if (file_exists($path)) {
            return response()->file($path, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
        abort(404);
    })->name('logo');
});
