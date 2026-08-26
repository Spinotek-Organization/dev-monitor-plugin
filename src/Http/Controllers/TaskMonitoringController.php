<?php

namespace Spinotek\TaskMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spinotek\TaskMonitoring\Models\MonitoringTask;
use Spinotek\TaskMonitoring\Services\VersionLogService;

class TaskMonitoringController extends Controller
{
    /**
     * Display the Vue SPA dashboard view.
     */
    public function dashboard(Request $request)
    {
        $activeTab = $request->is('monitoring/version-logs') ? 'version-logs' : 'tasks';

        $tasks = MonitoringTask::latest()->get();
        $stats = [
            'total' => $tasks->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
        ];

        $logs = VersionLogService::getLogs();
        $latestVersion = !empty($logs) ? ($logs[0]['version'] ?? 'v0.0.0') : 'v0.0.0';

        return view('task-monitoring::app', compact('tasks', 'stats', 'logs', 'latestVersion', 'activeTab'));
    }

    /**
     * Get tasks listing with stats via API.
     */
    public function apiIndex(Request $request)
    {
        $query = MonitoringTask::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('assigned_to', 'like', '%' . $request->search . '%');
            });
        }

        $allTasks = MonitoringTask::all();
        $stats = [
            'total' => $allTasks->count(),
            'pending' => $allTasks->where('status', 'pending')->count(),
            'in_progress' => $allTasks->where('status', 'in_progress')->count(),
            'completed' => $allTasks->where('status', 'completed')->count(),
        ];

        $tasks = $query->get();

        return response()->json([
            'success' => true,
            'data' => $tasks,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a newly created task via API.
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $task = MonitoringTask::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil ditambahkan!',
            'data' => $task,
        ], 201);
    }

    /**
     * Update the specified task via API.
     */
    public function apiUpdate(Request $request, MonitoringTask $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui!',
            'data' => $task,
        ]);
    }

    /**
     * Quick update status for a task via API.
     */
    public function apiUpdateStatus(Request $request, MonitoringTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui.',
            'task' => $task,
        ]);
    }

    /**
     * Remove the specified task via API.
     */
    public function apiDestroy(MonitoringTask $task)
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil dihapus!',
        ]);
    }
}
