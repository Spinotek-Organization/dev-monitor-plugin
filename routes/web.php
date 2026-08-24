<?php

use Illuminate\Support\Facades\Route;
use Spinotek\TaskMonitoring\Http\Controllers\TaskMonitoringController;
use Spinotek\TaskMonitoring\Http\Controllers\VersionLogController;

Route::middleware('web')->prefix('monitoring')->name('task-monitoring.')->group(function () {
    // Task Monitoring Routes
    Route::get('/', function () {
        return redirect()->route('task-monitoring.tasks.index');
    });

    Route::resource('tasks', TaskMonitoringController::class);
    Route::patch('tasks/{task}/status', [TaskMonitoringController::class, 'updateStatus'])->name('tasks.update-status');

    // Version Logs Routes
    Route::get('version-logs', [VersionLogController::class, 'index'])->name('version-logs.index');
    Route::post('version-logs', [VersionLogController::class, 'store'])->name('version-logs.store');
});
