@extends('task-monitoring::layout')

@section('title', 'Edit Task #' . $task->id)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Task #{{ $task->id }}</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi task monitoring.</p>
        </div>
        <a href="{{ route('task-monitoring.tasks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl shadow-2xs transition">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('task-monitoring.tasks.update', $task) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Judul Task *</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required placeholder="Contoh: Implementasi modul autentikasi..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Deskripsi</label>
                <textarea name="description" rows="4" placeholder="Rincian task atau checklist..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status *</label>
                    <select name="status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Prioritas *</label>
                    <select name="priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Assigned To</label>
                <input type="text" name="assigned_to" value="{{ old('assigned_to', $task->assigned_to) }}" placeholder="Nama developer / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('task-monitoring.tasks.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Perbarui Task
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
