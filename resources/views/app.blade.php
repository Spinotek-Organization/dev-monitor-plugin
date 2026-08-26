<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Monitoring & Version Logs - Spinotek</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        spinotek: {
                            50: '#f0f7ff',
                            100: '#e0effe',
                            200: '#bae0fd',
                            300: '#7cc5fb',
                            400: '#38a8f8',
                            500: '#0088ff',
                            600: '#0066ff',
                            700: '#0052cc',
                            800: '#0043a8',
                            900: '#00378a',
                            cyan: '#00b4ff',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        [v-cloak] { display: none; }
        @keyframes toastIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .animate-toast-in {
            animation: toastIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-toast-out {
            animation: toastOut 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .fade-enter-from {
            opacity: 0;
            transform: translateY(4px);
        }
        .fade-leave-to {
            opacity: 0;
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col antialiased">
    <div id="app" v-cloak class="min-h-screen flex flex-col">
        <!-- Floating Toast Notification Container -->
        <div class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0">
            <div v-for="toast in toasts" :key="toast.id"
                 class="pointer-events-auto bg-white rounded-2xl p-4 shadow-xl border flex items-start gap-3 transform transition-all duration-300 animate-toast-in"
                 :class="{
                     'border-blue-100 ring-1 ring-blue-500/10': toast.type === 'success',
                     'border-rose-100 ring-1 ring-rose-500/10': toast.type === 'error',
                     'border-slate-200 ring-1 ring-slate-500/10': toast.type === 'info'
                 }">
                <div class="p-2.5 rounded-xl flex-shrink-0"
                     :class="{
                         'bg-blue-50 text-blue-600': toast.type === 'success',
                         'bg-rose-50 text-rose-600': toast.type === 'error',
                         'bg-slate-100 text-slate-600': toast.type === 'info'
                     }">
                    <!-- Success SVG -->
                    <svg v-if="toast.type === 'success'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Error SVG -->
                    <svg v-else-if="toast.type === 'error'" class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <!-- Info SVG -->
                    <svg v-else class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </div>
                <div class="flex-1 pt-0.5 min-w-0">
                    <p class="text-sm font-bold text-slate-800 tracking-tight">@{{ toast.title }}</p>
                    <p class="text-xs text-slate-600 mt-0.5 leading-relaxed break-words">@{{ toast.message }}</p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition cursor-pointer" @click="removeToast(toast.id)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navbar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center p-1.5 shadow-sm flex-shrink-0">
                            <img src="{{ route('task-monitoring.logo') }}" alt="Spinotek Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <button @click="switchTab('tasks')" class="font-bold text-lg text-slate-900 tracking-tight hover:text-blue-600 transition flex items-center gap-2 cursor-pointer">
                                Spinotek <span class="text-blue-600 font-semibold text-xs px-2 py-0.5 bg-blue-50 rounded-md border border-blue-200">Monitoring</span>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Tabs (SPA Switching without reload) -->
                    <nav class="flex items-center space-x-2">
                        <button @click="switchTab('tasks')"
                                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium transition cursor-pointer"
                                :class="activeTab === 'tasks' ? 'bg-blue-50 text-blue-600 border border-blue-200 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Task Monitoring</span>
                        </button>
                        <button @click="switchTab('version-logs')"
                                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium transition cursor-pointer"
                                :class="activeTab === 'version-logs' ? 'bg-blue-50 text-blue-600 border border-blue-200 font-semibold shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386l5.06-2.981c.827-.486 1.055-1.547.494-2.296L11.16 4.591A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            <span>Version Logs</span>
                        </button>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main SPA Content Area -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <transition name="fade" mode="out-in">
                <!-- ================= TAB 1: TASK MONITORING ================= -->
                <div v-if="activeTab === 'tasks'" key="tab-tasks" class="space-y-6">
                    <!-- Header Section -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Task Monitoring</h1>
                            <p class="text-sm text-slate-500 mt-1">Pantau progres pengerjaan task, status pengerjaan, dan prioritas tugas secara realtime.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition cursor-pointer">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Tambah Task Baru</span>
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
                            <p class="text-3xl font-extrabold text-slate-900 mt-2">@{{ computedStats.total }}</p>
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
                            <p class="text-3xl font-extrabold text-slate-700 mt-2">@{{ computedStats.pending }}</p>
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
                            <p class="text-3xl font-extrabold text-amber-600 mt-2">@{{ computedStats.in_progress }}</p>
                        </div>

                        <!-- Completed -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Completed</span>
                                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-extrabold text-blue-600 mt-2">@{{ computedStats.completed }}</p>
                        </div>
                    </div>

                    <!-- Filters & Search (Instant Reactive) -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
                            <!-- Search -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <input type="text" v-model="searchQuery" placeholder="Cari judul / assignee..." class="w-full text-sm rounded-xl border border-slate-300 pl-10 pr-3.5 py-2.5 h-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            </div>

                            <!-- Status Select -->
                            <div>
                                <select v-model="filterStatus" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 h-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white cursor-pointer">
                                    <option value="">Semua Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <!-- Priority Select -->
                            <div>
                                <select v-model="filterPriority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 h-11 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white cursor-pointer">
                                    <option value="">Semua Prioritas</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <!-- Reset / Counter -->
                            <div class="flex gap-2 h-11">
                                <button v-if="searchQuery || filterStatus || filterPriority" @click="resetFilters" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    <span>Reset Filter</span>
                                </button>
                                <div v-else class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-slate-50 text-slate-500 text-xs font-semibold rounded-xl border border-slate-200">
                                    Menampilkan @{{ filteredTasks.length }} Task
                                </div>
                            </div>
                        </div>
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
                                    <tr v-for="task in filteredTasks" :key="task.id" class="hover:bg-slate-50/75 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900 text-sm sm:text-base">@{{ task.title }}</div>
                                            <p v-if="task.description" class="text-xs sm:text-sm text-slate-500 mt-0.5 line-clamp-1 leading-relaxed">@{{ task.description }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select :value="task.status" @change="updateTaskStatus(task, $event.target.value)"
                                                    class="text-xs font-semibold rounded-lg px-2.5 py-1.5 border cursor-pointer focus:ring-2 focus:ring-blue-500 outline-none transition"
                                                    :class="getStatusBadgeClass(task.status)">
                                                <option value="pending">Pending</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md border" :class="getPriorityBadgeClass(task.priority)">
                                                @{{ capitalize(task.priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs sm:text-sm font-medium text-slate-700">
                                            <span v-if="task.assigned_to" class="inline-flex items-center gap-2">
                                                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold flex items-center justify-center flex-shrink-0">
                                                    @{{ task.assigned_to.charAt(0).toUpperCase() }}
                                                </span>
                                                <span>@{{ task.assigned_to }}</span>
                                            </span>
                                            <span v-else class="text-slate-400 italic">Belum ditugaskan</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-500">
                                            @{{ formatDate(task.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                <!-- Edit Button -->
                                                <button type="button" @click="openEditModal(task)"
                                                        class="w-8 h-8 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition border border-slate-200 hover:border-blue-300 flex items-center justify-center cursor-pointer" 
                                                        title="Edit Task">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>

                                                <!-- Delete Button -->
                                                <button type="button" @click="openDeleteModal(task)"
                                                        class="w-8 h-8 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition border border-slate-200 hover:border-rose-300 flex items-center justify-center cursor-pointer" 
                                                        title="Hapus Task">
                                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredTasks.length === 0">
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                                </svg>
                                            </div>
                                            <p class="font-semibold text-slate-700 text-sm">Tidak ada data task yang sesuai.</p>
                                            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau klik tombol "Tambah Task Baru".</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 2: VERSION LOGS ================= -->
                <div v-else-if="activeTab === 'version-logs'" key="tab-version-logs" class="space-y-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Version Logs</h1>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    Latest: @{{ latestVersionComputed }}
                                </span>
                            </div>
                            <p class="text-sm sm:text-base text-slate-500 mt-1">Riwayat changelog & rilis versi aplikasi (disimpan dalam format file-based JSON).</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="openAddVersionModal" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-200 transition cursor-pointer">
                                <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span>Catat Versi Baru</span>
                            </button>
                        </div>
                    </div>

                    <!-- Summary Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Total Rilis -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Versi</span>
                                <span class="p-2 bg-slate-100 text-slate-600 rounded-xl">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386l5.06-2.981c.827-.486 1.055-1.547.494-2.296L11.16 4.591A2.25 2.25 0 009.568 3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-extrabold text-slate-900 mt-2">@{{ versionLogs.length }}</p>
                        </div>

                        <!-- Versi Terbaru -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Versi Terbaru</span>
                                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="text-3xl font-extrabold text-blue-600 mt-2 font-mono">@{{ latestVersionComputed }}</p>
                        </div>

                        <!-- Terakhir Diperbarui -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Terakhir Dirilis</span>
                                <span class="p-2 bg-sky-50 text-sky-600 rounded-xl">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                </span>
                            </div>
                            <p class="text-2xl font-bold text-slate-800 mt-3">@{{ versionLogs.length > 0 ? versionLogs[0].date : '-' }}</p>
                        </div>
                    </div>

                    <!-- Timeline of Versions -->
                    <div class="relative border-l-2 border-slate-200 ml-3 sm:ml-5 space-y-6 py-2">
                        <div v-for="(log, index) in versionLogs" :key="index" class="relative pl-6 sm:pl-8">
                            <!-- Dot icon -->
                            <div class="absolute -left-[9px] top-4 w-4 h-4 rounded-full bg-white border-4"
                                 :class="index === 0 ? 'border-blue-500 ring-4 ring-blue-100' : 'border-slate-400'"></div>

                            <!-- Version Card -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6 hover:shadow-md transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3.5">
                                    <div class="flex items-center gap-3">
                                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-mono tracking-tight">@{{ log.version }}</h2>
                                        <span class="text-xs uppercase font-semibold px-2.5 py-0.5 rounded-md border" :class="getVersionTypeBadgeClass(log.type)">
                                            @{{ capitalize(log.type || 'feature') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs sm:text-sm text-slate-500">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 flex-shrink-0 text-slate-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                            </svg>
                                            @{{ log.date || '-' }}
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300 flex-shrink-0"></span>
                                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                                            <svg class="w-4 h-4 flex-shrink-0 text-slate-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                            @{{ log.author || 'Unknown' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Changes list -->
                                <div class="mt-4">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">Daftar Perubahan (Changelog):</h4>
                                    <ul class="space-y-2">
                                        <li v-for="(change, cIndex) in (Array.isArray(log.changes) ? log.changes : [log.changes])" :key="cIndex" class="flex items-start gap-2.5 text-sm sm:text-base text-slate-700">
                                            <span class="p-0.5 bg-blue-50 text-blue-600 rounded border border-blue-200 mt-1 flex-shrink-0 flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </span>
                                            <span class="leading-relaxed">@{{ change }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div v-if="versionLogs.length === 0" class="pl-6 py-12 text-slate-400">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-700 text-sm">Belum ada riwayat versi yang tercatat.</p>
                            <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Versi Baru" untuk menambahkan catatan versi pertama.</p>
                        </div>
                    </div>
                </div>
            </transition>
        </main>

        <!-- ================= MODAL: CREATE TASK ================= -->
        <div v-if="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all animate-toast-in">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Tambah Task Baru</h3>
                    <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitCreateTask" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Task *</label>
                        <input type="text" v-model="newTask.title" required placeholder="Contoh: Implementasi modul autentikasi..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea v-model="newTask.description" rows="3" placeholder="Rincian task atau checklist..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status *</label>
                            <select v-model="newTask.status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas *</label>
                            <select v-model="newTask.priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned To</label>
                        <input type="text" v-model="newTask.assigned_to" placeholder="Nama developer / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmitting" class="inline-flex items-center gap-1.5 px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-blue-200 cursor-pointer">
                            <span v-if="isSubmitting">Menyimpan...</span>
                            <span v-else>Simpan Task</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL: EDIT TASK ================= -->
        <div v-if="showEditModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all animate-toast-in">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Edit Task #@{{ editingTask.id }}</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitUpdateTask" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Judul Task *</label>
                        <input type="text" v-model="editingTask.title" required placeholder="Contoh: Implementasi modul autentikasi..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea v-model="editingTask.description" rows="3" placeholder="Rincian task atau checklist..." class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Status *</label>
                            <select v-model="editingTask.status" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Prioritas *</label>
                            <select v-model="editingTask.priority" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigned To</label>
                        <input type="text" v-model="editingTask.assigned_to" placeholder="Nama developer / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmitting" class="inline-flex items-center gap-1.5 px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-blue-200 cursor-pointer">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span v-if="isSubmitting">Memperbarui...</span>
                            <span v-else>Perbarui Task</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= MODAL: DELETE CONFIRMATION ================= -->
        <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all text-center animate-toast-in">
                <!-- Danger Warning Icon -->
                <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <svg class="w-7 h-7 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900">Hapus Task Ini?</h3>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-slate-800">"@{{ deletingTask?.title }}"</span>? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mt-6 flex gap-3 justify-center">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                        Batal
                    </button>
                    <button type="button" :disabled="isSubmitting" @click="submitDeleteTask" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-rose-200 cursor-pointer">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        <span v-if="isSubmitting">Menghapus...</span>
                        <span v-else>Ya, Hapus</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= MODAL: ADD VERSION LOG ================= -->
        <div v-if="showAddVersionModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all animate-toast-in">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Catat Versi Baru</h3>
                    <button @click="showAddVersionModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer" title="Tutup">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitAddVersion" class="mt-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Versi *</label>
                            <input type="text" v-model="newVersion.version" required placeholder="v1.2.0" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal</label>
                            <input type="date" v-model="newVersion.date" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Author / Pembuat</label>
                            <input type="text" v-model="newVersion.author" placeholder="Nama Dev / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Rilis</label>
                            <select v-model="newVersion.type" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="feature">Feature</option>
                                <option value="improvement">Improvement</option>
                                <option value="fix">Bug Fix</option>
                                <option value="release">Major Release</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rincian Perubahan (1 baris per item) *</label>
                        <textarea v-model="newVersion.changesText" required rows="4" placeholder="Menambahkan fitur X&#10;Perbaikan bug Y&#10;Optimasi performa Z" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-sans"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showAddVersionModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmitting" class="inline-flex items-center gap-1.5 px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-sm font-semibold rounded-xl transition shadow-sm shadow-blue-200 cursor-pointer">
                            <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span v-if="isSubmitting">Menyimpan...</span>
                            <span v-else>Simpan Catatan Versi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-auto">
            <div class="flex items-center justify-center gap-2">
                <img src="{{ route('task-monitoring.logo') }}" alt="Spinotek Logo" class="w-4 h-4 object-contain">
                <p>&copy; {{ date('Y') }} Spinotek Dev Monitor Plugin. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Vue 3 Application Script -->
    <script>
        const { createApp, ref, computed, onMounted } = Vue;

        createApp({
            setup() {
                // Initial data hydrated from server
                const tasks = ref(@json($tasks));
                const versionLogs = ref(@json($logs));
                const activeTab = ref(@json($activeTab));

                // Filters
                const searchQuery = ref('');
                const filterStatus = ref('');
                const filterPriority = ref('');

                // Modal States
                const showCreateModal = ref(false);
                const showEditModal = ref(false);
                const showDeleteModal = ref(false);
                const showAddVersionModal = ref(false);
                const isSubmitting = ref(false);

                // Forms
                const newTask = ref({
                    title: '',
                    description: '',
                    status: 'pending',
                    priority: 'medium',
                    assigned_to: ''
                });

                const editingTask = ref({
                    id: null,
                    title: '',
                    description: '',
                    status: 'pending',
                    priority: 'medium',
                    assigned_to: ''
                });

                const deletingTask = ref(null);

                const newVersion = ref({
                    version: '',
                    date: new Date().toISOString().slice(0, 10),
                    author: 'Dev Spinotek',
                    type: 'feature',
                    changesText: ''
                });

                // Toast Notifications
                const toasts = ref([]);
                let toastId = 0;

                const notify = (message, type = 'success', title = null) => {
                    const id = ++toastId;
                    const defaultTitle = type === 'success' ? 'Berhasil' : (type === 'error' ? 'Gagal' : 'Pemberitahuan');
                    toasts.value.push({ id, message, type, title: title || defaultTitle });

                    setTimeout(() => {
                        removeToast(id);
                    }, 3500);
                };

                const removeToast = (id) => {
                    toasts.value = toasts.value.filter(t => t.id !== id);
                };

                // CSRF Token Helper
                const getCsrfToken = () => {
                    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                };

                // Navigation Switcher without reload
                const switchTab = (tab) => {
                    activeTab.value = tab;
                    const url = tab === 'version-logs' ? '/monitoring/version-logs' : '/monitoring/tasks';
                    window.history.pushState({ tab }, '', url);
                };

                // Handle Browser Back/Forward buttons
                window.addEventListener('popstate', (event) => {
                    if (window.location.pathname.includes('version-logs')) {
                        activeTab.value = 'version-logs';
                    } else {
                        activeTab.value = 'tasks';
                    }
                });

                // Computed: Filtered Tasks
                const filteredTasks = computed(() => {
                    return tasks.value.filter(task => {
                        const matchesSearch = !searchQuery.value || 
                            (task.title && task.title.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
                            (task.assigned_to && task.assigned_to.toLowerCase().includes(searchQuery.value.toLowerCase()));

                        const matchesStatus = !filterStatus.value || task.status === filterStatus.value;
                        const matchesPriority = !filterPriority.value || (task.priority && task.priority.toLowerCase() === filterPriority.value.toLowerCase());

                        return matchesSearch && matchesStatus && matchesPriority;
                    });
                });

                // Computed: Realtime Stats
                const computedStats = computed(() => {
                    const all = tasks.value;
                    return {
                        total: all.length,
                        pending: all.filter(t => t.status === 'pending').length,
                        in_progress: all.filter(t => t.status === 'in_progress').length,
                        completed: all.filter(t => t.status === 'completed').length,
                    };
                });

                // Computed: Latest Version
                const latestVersionComputed = computed(() => {
                    return versionLogs.value.length > 0 ? (versionLogs.value[0].version || 'v0.0.0') : 'v0.0.0';
                });

                const resetFilters = () => {
                    searchQuery.value = '';
                    filterStatus.value = '';
                    filterPriority.value = '';
                };

                // Helpers for Styling
                const getStatusBadgeClass = (status) => {
                    switch(status) {
                        case 'completed':
                            return 'bg-blue-50 text-blue-700 border-blue-300';
                        case 'in_progress':
                            return 'bg-amber-50 text-amber-700 border-amber-300';
                        default:
                            return 'bg-slate-100 text-slate-700 border-slate-300';
                    }
                };

                const getPriorityBadgeClass = (priority) => {
                    switch((priority || '').toLowerCase()) {
                        case 'high':
                            return 'bg-rose-50 text-rose-700 border-rose-200';
                        case 'medium':
                            return 'bg-blue-50 text-blue-700 border-blue-200';
                        default:
                            return 'bg-slate-100 text-slate-700 border-slate-200';
                    }
                };

                const getVersionTypeBadgeClass = (type) => {
                    switch((type || '').toLowerCase()) {
                        case 'release':
                            return 'bg-purple-50 text-purple-700 border-purple-200';
                        case 'fix':
                            return 'bg-rose-50 text-rose-700 border-rose-200';
                        case 'improvement':
                            return 'bg-amber-50 text-amber-700 border-amber-200';
                        default:
                            return 'bg-blue-50 text-blue-700 border-blue-200';
                    }
                };

                const capitalize = (str) => {
                    if (!str) return '';
                    return str.charAt(0).toUpperCase() + str.slice(1);
                };

                const formatDate = (dateStr) => {
                    if (!dateStr) return '-';
                    const d = new Date(dateStr);
                    if (isNaN(d.getTime())) return dateStr;
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                };

                // Actions: Create Task
                const openCreateModal = () => {
                    newTask.value = {
                        title: '',
                        description: '',
                        status: 'pending',
                        priority: 'medium',
                        assigned_to: ''
                    };
                    showCreateModal.value = true;
                };

                const submitCreateTask = async () => {
                    isSubmitting.value = true;
                    try {
                        const response = await fetch('/api/monitoring/tasks', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify(newTask.value)
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            tasks.value.unshift(res.data);
                            showCreateModal.value = false;
                            notify(res.message || 'Task berhasil ditambahkan!', 'success');
                        } else {
                            notify(res.message || 'Gagal menyimpan task', 'error');
                        }
                    } catch (e) {
                        notify('Terjadi kesalahan jaringan saat menyimpan task.', 'error');
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                // Actions: Edit Task
                const openEditModal = (task) => {
                    editingTask.value = { ...task, priority: (task.priority || 'medium').toLowerCase() };
                    showEditModal.value = true;
                };

                const submitUpdateTask = async () => {
                    isSubmitting.value = true;
                    try {
                        const response = await fetch(`/api/monitoring/tasks/${editingTask.value.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify(editingTask.value)
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            const idx = tasks.value.findIndex(t => t.id === editingTask.value.id);
                            if (idx !== -1) {
                                tasks.value[idx] = res.data;
                            }
                            showEditModal.value = false;
                            notify(res.message || 'Task berhasil diperbarui!', 'success');
                        } else {
                            notify(res.message || 'Gagal memperbarui task', 'error');
                        }
                    } catch (e) {
                        notify('Terjadi kesalahan jaringan saat memperbarui task.', 'error');
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                // Actions: Quick Status Update
                const updateTaskStatus = async (task, newStatus) => {
                    const prevStatus = task.status;
                    task.status = newStatus;

                    try {
                        const response = await fetch(`/api/monitoring/tasks/${task.id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify({ status: newStatus })
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            notify(`Status task berhasil diubah ke ${capitalize(newStatus)}.`, 'success');
                        } else {
                            task.status = prevStatus;
                            notify(res.message || 'Gagal mengubah status', 'error');
                        }
                    } catch (e) {
                        task.status = prevStatus;
                        notify('Gagal mengubah status task.', 'error');
                    }
                };

                // Actions: Delete Task
                const openDeleteModal = (task) => {
                    deletingTask.value = task;
                    showDeleteModal.value = true;
                };

                const submitDeleteTask = async () => {
                    if (!deletingTask.value) return;
                    isSubmitting.value = true;

                    try {
                        const response = await fetch(`/api/monitoring/tasks/${deletingTask.value.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            }
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            tasks.value = tasks.value.filter(t => t.id !== deletingTask.value.id);
                            showDeleteModal.value = false;
                            notify(res.message || 'Task berhasil dihapus!', 'success');
                        } else {
                            notify(res.message || 'Gagal menghapus task', 'error');
                        }
                    } catch (e) {
                        notify('Terjadi kesalahan saat menghapus task.', 'error');
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                // Actions: Add Version Log
                const openAddVersionModal = () => {
                    newVersion.value = {
                        version: '',
                        date: new Date().toISOString().slice(0, 10),
                        author: 'Dev Spinotek',
                        type: 'feature',
                        changesText: ''
                    };
                    showAddVersionModal.value = true;
                };

                const submitAddVersion = async () => {
                    isSubmitting.value = true;
                    try {
                        const changes = newVersion.value.changesText
                            .split('\n')
                            .map(line => line.trim())
                            .filter(line => line.length > 0);

                        const payload = {
                            version: newVersion.value.version,
                            date: newVersion.value.date,
                            author: newVersion.value.author,
                            type: newVersion.value.type,
                            changes: changes
                        };

                        const response = await fetch('/api/monitoring/version-logs', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify(payload)
                        });

                        const res = await response.json();
                        if (response.ok && res.success) {
                            versionLogs.value.unshift(res.data);
                            showAddVersionModal.value = false;
                            notify(res.message || 'Catatan versi baru berhasil ditambahkan!', 'success');
                        } else {
                            notify(res.message || 'Gagal menambahkan versi baru', 'error');
                        }
                    } catch (e) {
                        notify('Terjadi kesalahan jaringan saat menambahkan versi.', 'error');
                    } finally {
                        isSubmitting.value = false;
                    }
                };

                return {
                    activeTab,
                    switchTab,
                    tasks,
                    versionLogs,
                    searchQuery,
                    filterStatus,
                    filterPriority,
                    filteredTasks,
                    computedStats,
                    latestVersionComputed,
                    resetFilters,
                    toasts,
                    removeToast,
                    getStatusBadgeClass,
                    getPriorityBadgeClass,
                    getVersionTypeBadgeClass,
                    capitalize,
                    formatDate,
                    showCreateModal,
                    showEditModal,
                    showDeleteModal,
                    showAddVersionModal,
                    isSubmitting,
                    newTask,
                    editingTask,
                    deletingTask,
                    newVersion,
                    openCreateModal,
                    submitCreateTask,
                    openEditModal,
                    submitUpdateTask,
                    updateTaskStatus,
                    openDeleteModal,
                    submitDeleteTask,
                    openAddVersionModal,
                    submitAddVersion
                };
            }
        }).mount('#app');
    </script>
</body>
</html>
