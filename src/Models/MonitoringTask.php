<?php

namespace Spinotek\TaskMonitoring\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringTask extends Model
{
    use HasFactory;

    protected $table = 'monitoring_tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
    ];

    /**
     * Get badge color class based on status for Tailwind CSS.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800',
            'in_progress' => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800',
            default => 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }

    /**
     * Get badge color class based on priority for Tailwind CSS.
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match (strtolower($this->priority)) {
            'high' => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950 dark:text-rose-300 dark:border-rose-800',
            'medium' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-950 dark:text-blue-300 dark:border-blue-800',
            default => 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700',
        };
    }
}
