@extends('task-monitoring::layout')

@section('title', 'Daftar Monitoring Task')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Task Monitoring</h1>
            <p class="text-sm sm:text-base text-slate-500 mt-1">Pantau progres pengerjaan task, status pengerjaan, dan prioritas tugas secara realtime.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('createTaskModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition cursor-pointer">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Task Baru
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Task -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Task</span>
                <span class="p-2 bg-slate-100 text-slate-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ $stats['total'] }}</p>
        </div>

        <!-- Pending -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending</span>
                <span class="p-2 bg-slate-100 text-slate-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-700 mt-2">{{ $stats['pending'] }}</p>
        </div>

        <!-- In Progress -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">In Progress</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-amber-600 mt-2">{{ $stats['in_progress'] }}</p>
        </div>

        <!-- Completed -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $stats['completed'] }}</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('task-monitoring.tasks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
            <!-- Search input -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul / assignee..." class="w-full text-sm rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 h-11 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
            </div>

            <!-- Status Select -->
            <div>
                <select name="status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 h-11 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <!-- Priority Select -->
            <div>
                <select name="priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 h-11 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer">
                    <option value="">Semua Prioritas</option>
                    <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 h-11">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'priority']))
                    <a href="{{ route('task-monitoring.tasks.index') }}" class="w-11 inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition" title="Reset filter">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase text-slate-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Task</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Prioritas</th>
                        <th class="px-6 py-4">Assigned To</th>
                        <th class="px-6 py-4">Dibuat</th>
                        <th class="px-6 py-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm sm:text-base">{{ $task->title }}</div>
                                @if($task->description)
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5 line-clamp-1 leading-relaxed">{{ $task->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('task-monitoring.tasks.update-status', $task) }}" class="inline-block m-0">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-lg px-2.5 py-1.5 border cursor-pointer {{ $task->status_badge_class }} focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-md border {{ $task->priority_badge_class }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs sm:text-sm font-medium text-slate-700">
                                @if($task->assigned_to)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-slate-200 text-[11px] font-bold flex items-center justify-center text-slate-700 flex-shrink-0">
                                            {{ strtoupper(substr($task->assigned_to, 0, 1)) }}
                                        </span>
                                        <span>{{ $task->assigned_to }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ $task->created_at ? $task->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center gap-1.5">
                                    <!-- Edit Button (Opens Edit Modal) -->
                                    <button type="button" 
                                            onclick='openEditModal(@json($task))'
                                            class="w-8 h-8 rounded-lg text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition border border-slate-200 hover:border-emerald-300 flex items-center justify-center cursor-pointer" 
                                            title="Edit Task">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <!-- Custom Delete Modal Trigger -->
                                    <button type="button" 
                                            onclick="openDeleteModal({{ $task->id }}, '{{ addslashes($task->title) }}')"
                                            class="w-8 h-8 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 hover:border-rose-300 flex items-center justify-center cursor-pointer" 
                                            title="Hapus Task">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <p class="font-semibold text-slate-700 text-sm">Belum ada data task.</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Task Baru" di atas untuk menambahkan tugas pertama Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Create Task -->
<div id="createTaskModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all">
        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Tambah Task Baru</h3>
            <button onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('task-monitoring.tasks.store') }}" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Task *</label>
                <input type="text" name="title" required placeholder="Contoh: Implementasi modul autentikasi..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Rincian task atau checklist..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status *</label>
                    <select name="status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas *</label>
                    <select name="priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned To</label>
                <input type="text" name="assigned_to" placeholder="Nama developer / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm cursor-pointer">
                    Simpan Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Task -->
<div id="editTaskModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all">
        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900" id="editModalHeading">Edit Task</h3>
            <button onclick="document.getElementById('editTaskModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none cursor-pointer">&times;</button>
        </div>

        <form id="editTaskForm" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Task *</label>
                <input type="text" name="title" id="edit_title" required placeholder="Contoh: Implementasi modul autentikasi..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="3" placeholder="Rincian task atau checklist..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status *</label>
                    <select name="status" id="edit_status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas *</label>
                    <select name="priority" id="edit_priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned To</label>
                <input type="text" name="assigned_to" id="edit_assigned_to" placeholder="Nama developer / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editTaskModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Perbarui Task
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Modal Delete Confirmation -->
<div id="deleteTaskModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all text-center">
        <!-- Danger Warning Icon -->
        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
            <svg class="w-7 h-7 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </div>

        <h3 class="text-xl font-bold text-slate-900">Hapus Task Ini?</h3>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Apakah Anda yakin ingin menghapus <span id="deleteTaskTitle" class="font-bold text-slate-800"></span>? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form id="deleteTaskForm" method="POST" action="" class="mt-6 flex gap-3 justify-center">
            @csrf
            @method('DELETE')
            <button type="button" onclick="document.getElementById('deleteTaskModal').classList.add('hidden')" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                Batal
            </button>
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-rose-200 cursor-pointer">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
                Ya, Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(task) {
        const modal = document.getElementById('editTaskModal');
        const form = document.getElementById('editTaskForm');
        const heading = document.getElementById('editModalHeading');

        // Set action URL to /monitoring/tasks/{id}
        form.action = "{{ url('/monitoring/tasks') }}/" + task.id;
        heading.innerText = 'Edit Task #' + task.id;

        // Populate fields
        document.getElementById('edit_title').value = task.title || '';
        document.getElementById('edit_description').value = task.description || '';
        document.getElementById('edit_status').value = task.status || 'pending';
        document.getElementById('edit_priority').value = (task.priority || 'medium').toLowerCase();
        document.getElementById('edit_assigned_to').value = task.assigned_to || '';

        modal.classList.remove('hidden');
    }

    function openDeleteModal(id, title) {
        const modal = document.getElementById('deleteTaskModal');
        const form = document.getElementById('deleteTaskForm');
        const titleSpan = document.getElementById('deleteTaskTitle');

        form.action = "{{ url('/monitoring/tasks') }}/" + id;
        titleSpan.innerText = `"${title}"`;

        modal.classList.remove('hidden');
    }
</script>
@endsection
