<?php

use Illuminate\Support\Facades\Route;
use Spinotek\TaskMonitoring\Http\Controllers\TaskMonitoringController;
use Spinotek\TaskMonitoring\Http\Controllers\VersionLogController;

Route::middleware('api')->prefix('api/monitoring')->name('api.task-monitoring.')->group(function () {
    // Tasks API
    Route::get('tasks', [TaskMonitoringController::class, 'apiIndex'])->name('tasks.index');
    Route::post('tasks', [TaskMonitoringController::class, 'apiStore'])->name('tasks.store');
    Route::put('tasks/{task}', [TaskMonitoringController::class, 'apiUpdate'])->name('tasks.update');
    Route::patch('tasks/{task}/status', [TaskMonitoringController::class, 'apiUpdateStatus'])->name('tasks.update-status');
    Route::delete('tasks/{task}', [TaskMonitoringController::class, 'apiDestroy'])->name('tasks.destroy');

    // Version Logs API
    Route::get('version-logs', [VersionLogController::class, 'apiIndex'])->name('version-logs.index');
    Route::post('version-logs', [VersionLogController::class, 'apiStore'])->name('version-logs.store');
});
