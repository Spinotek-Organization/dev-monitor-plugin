<?php

use Illuminate\Support\Facades\Route;
use Spinotek\TaskMonitoring\Http\Controllers\TaskMonitoringController;

Route::middleware('web')->prefix('monitoring')->name('task-monitoring.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('task-monitoring.dashboard');
    });

    Route::get('dashboard', [TaskMonitoringController::class, 'dashboard'])->name('dashboard');
    Route::get('tasks', [TaskMonitoringController::class, 'dashboard'])->name('tasks.index');
    Route::get('version-logs', [TaskMonitoringController::class, 'dashboard'])->name('version-logs.index');
});
