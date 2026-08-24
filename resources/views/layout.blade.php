<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Monitoring & Version Logs') - Spinotek</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        html { font-size: 16.5px; }
        body { font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        @keyframes toastIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .animate-toast-in {
            animation: toastIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-toast-out {
            animation: toastOut 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col antialiased">
    <!-- Floating Toast Notification Container -->
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none px-4 sm:px-0"></div>

    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                    <div>
                        <a href="{{ route('task-monitoring.tasks.index') }}" class="font-bold text-xl text-slate-800 tracking-tight hover:text-emerald-600 transition flex items-center gap-2">
                            Spinotek <span class="text-emerald-700 font-semibold text-xs px-2.5 py-0.5 bg-emerald-50 rounded-md border border-emerald-200">Monitoring</span>
                        </a>
                    </div>
                </div>

                <nav class="flex items-center space-x-2">
                    <a href="{{ route('task-monitoring.tasks.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[15px] font-medium transition {{ request()->routeIs('task-monitoring.tasks.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Task Monitoring</span>
                    </a>
                    <a href="{{ route('task-monitoring.version-logs.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[15px] font-medium transition {{ request()->routeIs('task-monitoring.version-logs.*') ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386l5.06-2.981c.827-.486 1.055-1.547.494-2.296L11.16 4.591A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        <span>Version Logs</span>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-sm text-slate-500">
        <p>&copy; {{ date('Y') }} Spinotek Task Monitoring Plugin. Built with Laravel.</p>
    </footer>

    <!-- Toast Notification Engine -->
    <script>
        window.notify = function(message, type = 'success', duration = 3500) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'pointer-events-auto bg-white rounded-2xl p-4 shadow-xl border flex items-start gap-3 transform transition-all duration-300 animate-toast-in';
            
            let iconSvg = '';
            let borderColor = 'border-slate-200';
            let iconBg = 'bg-slate-100 text-slate-600';
            let titleText = 'Pemberitahuan';

            if (type === 'success') {
                borderColor = 'border-emerald-100 ring-1 ring-emerald-500/10';
                iconBg = 'bg-emerald-50 text-emerald-600';
                titleText = 'Berhasil';
                iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>`;
            } else if (type === 'error') {
                borderColor = 'border-rose-100 ring-1 ring-rose-500/10';
                iconBg = 'bg-rose-50 text-rose-600';
                titleText = 'Gagal';
                iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>`;
            } else {
                borderColor = 'border-blue-100 ring-1 ring-blue-500/10';
                iconBg = 'bg-blue-50 text-blue-600';
                titleText = 'Informasi';
                iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>`;
            }

            toast.classList.add(...borderColor.split(' '));

            toast.innerHTML = `
                <div class="p-2.5 rounded-xl ${iconBg} flex-shrink-0">
                    ${iconSvg}
                </div>
                <div class="flex-1 pt-0.5 min-w-0">
                    <p class="text-sm font-bold text-slate-800 tracking-tight">${titleText}</p>
                    <p class="text-[13.5px] text-slate-600 mt-0.5 leading-relaxed break-words">${message}</p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition" onclick="closeToast(this.parentElement)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;

            container.appendChild(toast);

            const autoDismissTimer = setTimeout(() => {
                closeToast(toast);
            }, duration);

            window.closeToast = function(el) {
                if (!el) return;
                clearTimeout(autoDismissTimer);
                el.classList.remove('animate-toast-in');
                el.classList.add('animate-toast-out');
                setTimeout(() => {
                    if (el.parentElement) el.remove();
                }, 250);
            };
        };

        // Trigger session flash toasts automatically
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                window.notify(@json(session('success')), 'success');
            @endif

            @if(session('error'))
                window.notify(@json(session('error')), 'error');
            @endif

            @if(isset($errors) && $errors->any())
                @foreach($errors->all() as $error)
                    window.notify(@json($error), 'error');
                @endforeach
            @endif
        });
    </script>

    @yield('scripts')
</body>
</html>
