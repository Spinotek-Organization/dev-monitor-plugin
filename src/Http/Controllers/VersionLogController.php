<?php

namespace Spinotek\TaskMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spinotek\TaskMonitoring\Services\VersionLogService;

class VersionLogController extends Controller
{
    /**
     * Display the version logs timeline.
     */
    public function index()
    {
        $logs = VersionLogService::getLogs();
        $latestVersion = !empty($logs) ? ($logs[0]['version'] ?? 'v0.0.0') : 'v0.0.0';

        return view('task-monitoring::version-logs.index', compact('logs', 'latestVersion'));
    }

    /**
     * Store a new version log via web form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'date' => 'nullable|date',
            'author' => 'nullable|string|max:100',
            'type' => 'nullable|string|in:feature,improvement,fix,release',
            'changes' => 'required|string',
        ]);

        VersionLogService::addLog($validated);

        return redirect()->route('task-monitoring.version-logs.index')
            ->with('success', 'Riwayat versi baru berhasil ditambahkan ke version_logs.json!');
    }

    /**
     * Store a new version log via API endpoint (for AI Agents / CI).
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:50',
            'date' => 'nullable|date',
            'author' => 'nullable|string|max:100',
            'type' => 'nullable|string',
            'changes' => 'required',
        ]);

        $entry = VersionLogService::addLog($validated);

        return response()->json([
            'success' => true,
            'message' => 'Version log successfully recorded.',
            'data' => $entry,
        ], 201);
    }

    /**
     * Get all version logs via API endpoint.
     */
    public function apiIndex()
    {
        return response()->json([
            'success' => true,
            'data' => VersionLogService::getLogs(),
        ]);
    }
}
