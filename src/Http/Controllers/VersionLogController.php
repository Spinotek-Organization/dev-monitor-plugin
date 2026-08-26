<?php

namespace Spinotek\TaskMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spinotek\TaskMonitoring\Services\VersionLogService;

class VersionLogController extends Controller
{
    /**
     * Get all version logs via API.
     */
    public function apiIndex()
    {
        return response()->json([
            'success' => true,
            'data' => VersionLogService::getLogs(),
        ]);
    }

    /**
     * Store a new version log via API.
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'date' => 'nullable|date',
            'author' => 'nullable|string|max:100',
            'type' => 'nullable|string|in:feature,improvement,fix,release',
            'changes' => 'required',
        ]);

        $entry = VersionLogService::addLog($validated);

        return response()->json([
            'success' => true,
            'message' => 'Catatan versi baru berhasil ditambahkan!',
            'data' => $entry,
        ], 201);
    }
}
