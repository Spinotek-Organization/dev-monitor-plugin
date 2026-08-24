<?php

namespace Spinotek\TaskMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spinotek\TaskMonitoring\Models\MonitoringTask;

class TaskMonitoringController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(Request $request)
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

        $tasks = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => MonitoringTask::count(),
            'pending' => MonitoringTask::where('status', 'pending')->count(),
            'in_progress' => MonitoringTask::where('status', 'in_progress')->count(),
            'completed' => MonitoringTask::where('status', 'completed')->count(),
        ];

        return view('task-monitoring::tasks.index', compact('tasks', 'stats'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('task-monitoring::tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        MonitoringTask::create($validated);

        return redirect()->route('task-monitoring.tasks.index')
            ->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(MonitoringTask $task)
    {
        return view('task-monitoring::tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, MonitoringTask $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|string|max:255',
        ]);

        $task->update($validated);

        return redirect()->route('task-monitoring.tasks.index')
            ->with('success', 'Task berhasil diperbarui!');
    }

    /**
     * Quick update status for a task.
     */
    public function updateStatus(Request $request, MonitoringTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status task berhasil diperbarui.',
                'task' => $task,
            ]);
        }

        return back()->with('success', 'Status task berhasil diperbarui!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(MonitoringTask $task)
    {
        $task->delete();

        return redirect()->route('task-monitoring.tasks.index')
            ->with('success', 'Task berhasil dihapus!');
    }
}
