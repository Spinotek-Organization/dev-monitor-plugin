@extends('task-monitoring::layout')

@section('title', 'Riwayat Versi (Version Logs)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Version Logs</h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Latest: {{ $latestVersion }}
                </span>
            </div>
            <p class="text-sm sm:text-base text-slate-500 mt-1">Riwayat changelog & rilis versi aplikasi (disimpan dalam format file-based JSON).</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('addVersionModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition cursor-pointer">
                <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Catat Versi Baru
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
            <p class="text-3xl font-extrabold text-slate-900 mt-2">{{ count($logs) }}</p>
        </div>

        <!-- Versi Terbaru -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Versi Terbaru</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600 mt-2 font-mono">{{ $latestVersion }}</p>
        </div>

        <!-- Terakhir Diperbarui -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Terakhir Dirilis</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-bold text-slate-800 mt-3">
                {{ !empty($logs) ? ($logs[0]['date'] ?? '-') : '-' }}
            </p>
        </div>
    </div>

    <!-- Timeline of Versions -->
    <div class="relative border-l-2 border-slate-200 ml-3 sm:ml-5 space-y-6 py-2">
        @forelse($logs as $index => $log)
            @php
                $type = $log['type'] ?? 'feature';
                $badgeClasses = match($type) {
                    'release' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'fix' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'improvement' => 'bg-amber-50 text-amber-700 border-amber-200',
                    default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                };
            @endphp
            <div class="relative pl-6 sm:pl-8">
                <!-- Dot icon -->
                <div class="absolute -left-[9px] top-4 w-4 h-4 rounded-full bg-white border-4 {{ $index === 0 ? 'border-emerald-500 ring-4 ring-emerald-100' : 'border-slate-400' }}"></div>

                <!-- Version Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:p-6 hover:shadow-md transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3.5">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 font-mono tracking-tight">{{ $log['version'] }}</h2>
                            <span class="text-xs uppercase font-semibold px-2.5 py-0.5 rounded-md border {{ $badgeClasses }}">
                                {{ ucfirst($type) }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-xs sm:text-sm text-slate-500">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ $log['date'] ?? '-' }}
                            </span>
                            <span>&bull;</span>
                            <span class="inline-flex items-center gap-1.5 font-medium text-slate-700">
                                <svg class="w-4 h-4 flex-shrink-0 text-slate-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                {{ $log['author'] ?? 'Unknown' }}
                            </span>
                        </div>
                    </div>

                    <!-- Changes list -->
                    <div class="mt-4">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">Daftar Perubahan (Changelog):</h4>
                        <ul class="space-y-2">
                            @if(isset($log['changes']) && is_array($log['changes']))
                                @foreach($log['changes'] as $change)
                                    <li class="flex items-start gap-2.5 text-sm sm:text-base text-slate-700">
                                        <span class="p-0.5 bg-emerald-50 text-emerald-600 rounded border border-emerald-200 mt-1 flex-shrink-0 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </span>
                                        <span class="leading-relaxed">{{ $change }}</span>
                                    </li>
                                @endforeach
                            @elseif(isset($log['changes']))
                                <li class="text-sm sm:text-base text-slate-700 leading-relaxed">{{ $log['changes'] }}</li>
                            @else
                                <li class="text-sm text-slate-400 italic">Tidak ada catatan rincian perubahan.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="pl-6 py-12 text-slate-400">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="font-semibold text-slate-700 text-sm">Belum ada riwayat versi yang tercatat.</p>
                <p class="text-xs text-slate-400 mt-1">Klik tombol "Catat Versi Baru" untuk menambahkan catatan versi pertama.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Add Version Log -->
<div id="addVersionModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 transform transition-all">
        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Catat Versi Baru</h3>
            <button onclick="document.getElementById('addVersionModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-xl font-bold leading-none cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('task-monitoring.version-logs.store') }}" class="mt-4 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Versi *</label>
                    <input type="text" name="version" required placeholder="v1.2.0" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Author / Pembuat</label>
                    <input type="text" name="author" value="Dev Spinotek" placeholder="Nama Dev / AI Agent" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tipe Rilis</label>
                    <select name="type" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none bg-white">
                        <option value="feature">Feature</option>
                        <option value="improvement">Improvement</option>
                        <option value="fix">Bug Fix</option>
                        <option value="release">Major Release</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Rincian Perubahan (1 baris per item) *</label>
                <textarea name="changes" required rows="4" placeholder="Menambahkan fitur X&#10;Perbaikan bug Y&#10;Optimasi performa Z" class="w-full text-sm rounded-xl border border-slate-300 px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-sans"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addVersionModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Catatan Versi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
