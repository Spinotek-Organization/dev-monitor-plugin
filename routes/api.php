<?php

use Illuminate\Support\Facades\Route;
use Spinotek\TaskMonitoring\Http\Controllers\VersionLogController;

Route::middleware('api')->prefix('api/monitoring')->name('api.task-monitoring.')->group(function () {
    Route::get('version-logs', [VersionLogController::class, 'apiIndex'])->name('version-logs.index');
    Route::post('version-logs', [VersionLogController::class, 'apiStore'])->name('version-logs.store');
});
