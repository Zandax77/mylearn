<!DOCTYPE html>

{{-- Provide default $school for safety when landing page is rendered without SchoolSetting binding (e.g. during tests) --}}
@php
    $school = $school ?? (object) ['name' => config('app.name', 'School LMS'), 'logo' => null];
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $school->name }} — LMS</title>

        <meta name="description" content="{{ $school->name }} LMS - Minimalist learning management system for a better educational experience." />
        <meta name="robots" content="index,follow" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            :root {
                --primary: #1b1b18;
                --accent: #2563eb;
                --bg-soft: #f8fafc;
                --glass: rgba(255, 255, 255, 0.7);
                --glass-border: rgba(255, 255, 255, 0.3);
            }

            @media (prefers-color-scheme: dark) {
                :root {
                    --primary: #f8fafc;
                    --accent: #60a5fa;
                    --bg-soft: #0a0a0a;
                    --glass: rgba(15, 15, 15, 0.7);
                    --glass-border: rgba(255, 255, 255, 0.1);
                }
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--bg-soft);
                color: var(--primary);
                overflow-x: hidden;
            }

            h1, h2, h3, .font-outfit {
                font-family: 'Outfit', sans-serif;
            }

            .glass-card {
                background: var(--glass);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid var(--glass-border);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
            }

            .hero-gradient {
                background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.05), transparent),
                            radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.03), transparent);
            }


            .btn-primary {
                background: var(--primary);
                color: var(--bg-soft);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 24px;
                font-weight: 600;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.3);
                opacity: 0.9;
            }

            .btn-secondary {
                border: 1px solid var(--glass-border);
                background: var(--glass);
                transition: all 0.3s ease;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 10px 24px;
                font-weight: 600;
            }

            .btn-secondary:hover {
                background: var(--glass-border);
                transform: translateY(-2px);
            }

            .nav-link {
                position: relative;
                transition: color 0.3s ease;
            }

            .nav-link::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 0;
                width: 0;
                height: 2px;
                background: var(--accent);
                transition: width 0.3s ease;
            }

            .nav-link:hover::after {
                width: 100%;
            }

            /* Mobile Improvements */
            @media (max-width: 640px) {
                .header-nav {
                    padding: 8px 16px;
                }
                .school-name {
                    font-size: 1rem;
                    max-width: 150px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                h1 {
                    font-size: 2.5rem;
                }
                .hero-section {
                    padding-top: 120px;
                }
            }

            @keyframes gentle-float {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-5px) rotate(2deg);
                }
            }

            .animated-logo {
                animation: gentle-float 3s ease-in-out infinite;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .animated-logo:hover {
                transform: scale(1.08) translateY(-3px) rotate(-1deg);
                box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.25);
            }
        </style>
    </head>

    <body class="antialiased hero-gradient min-h-screen flex flex-col">
        <!-- Minimalist Navigation -->
        <header class="fixed top-0 w-full z-50 px-4 py-4 sm:px-6">
            <nav class="mx-auto max-w-6xl glass-card rounded-2xl px-4 py-3 sm:px-6 flex items-center justify-between header-nav">
                <a href="/" class="flex items-center gap-2 sm:gap-4 group">
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-md flex items-center justify-center overflow-hidden shrink-0 animated-logo">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/school_logo_placeholder.svg') }}" alt="School Logo" class="w-full h-full p-2.5">
                        @endif
                    </div>
                    <span class="text-lg sm:text-xl font-bold tracking-tight font-outfit school-name transition-colors group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $school->name }}</span>
                </a>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary text-sm">
                                Log in
                            </a>
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col items-center justify-center pt-32 sm:pt-44 pb-20 px-6 hero-section">
            <div class="max-w-4xl w-full text-center">
                <h1 class="text-4xl sm:text-5xl md:text-7xl font-bold tracking-tight mb-8 bg-clip-text text-transparent bg-gradient-to-b from-current to-gray-500 leading-tight">
                    Pengalaman Belajar <br class="hidden md:block"> yang Lebih Bermakna
                </h1>

                {{-- Removed marketing tagline as requested --}}

                {{-- Removed middle Log in button --}}


            </div>

            <!-- Stats Cards -->
            <div class="mt-20 sm:mt-24 w-full max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Make cards shorter and align into one row on desktop --}}
                <div class="glass-card p-6 sm:p-8 rounded-3xl">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div class="inline-flex items-center gap-2 text-blue-700/90 text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>Total Siswa</span>
                    </div>
                    <h3 class="text-3xl font-bold mt-2 font-outfit text-blue-950">{{ number_format($totalSiswa ?? 0) }}</h3>
                </div>

                <div class="glass-card p-6 sm:p-8 rounded-3xl">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <path d="M12 7v10"></path>
                            <path d="M8 7h8"></path>
                            <circle cx="12" cy="5" r="2"></circle>
                        </svg>
                    </div>
                    <div class="inline-flex items-center gap-2 text-emerald-700/90 text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Total Guru</span>
                    </div>
                    <h3 class="text-3xl font-bold mt-2 font-outfit text-emerald-950">{{ number_format($totalGuru ?? 0) }}</h3>
                </div>

                <div class="glass-card p-6 sm:p-8 rounded-3xl">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-5">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                            <path d="M8 8h8"></path>
                            <path d="M8 12h5"></path>
                        </svg>
                    </div>
                    <div class="inline-flex items-center gap-2 text-rose-700/90 text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Total Kelas</span>
                    </div>
                    <h3 class="text-3xl font-bold mt-2 font-outfit text-rose-950">{{ number_format($totalKelas ?? 0) }}</h3>
                </div>

                <div class="glass-card p-6 sm:p-8 rounded-3xl">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-5">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </div>
                    <div class="inline-flex items-center gap-2 text-purple-700/90 text-sm font-semibold">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>Total Mapel</span>
                    </div>
                    <h3 class="text-3xl font-bold mt-2 font-outfit text-purple-950">{{ number_format($totalMapel ?? 0) }}</h3>
                </div>
            </div>

            <!-- LMS Activity (last 7 days) -->
            <div class="mt-20 sm:mt-24 w-full max-w-5xl mx-auto">
                <div class="glass-card p-6 sm:p-8 rounded-3xl">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl sm:text-3xl font-bold font-outfit">Aktivitas LMS (7 hari terakhir)</h2>
                            <p class="text-sm opacity-60 leading-relaxed">Ringkasan aktivitas siswa dan guru selama 7 hari terakhir.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="glass-card p-5 sm:p-6 rounded-3xl">
                            <div class="text-sm font-semibold mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                <span>Aktivitas Siswa</span>
                            </div>
                            <div class="h-64 w-full">
                                <canvas id="siswaLmsActivity7d"></canvas>
                            </div>
                        </div>

                        <div class="glass-card p-5 sm:p-6 rounded-3xl">
                            <div class="text-sm font-semibold mb-3 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Aktivitas Guru</span>
                            </div>
                            <div class="h-64 w-full">
                                <canvas id="guruLmsActivity7d"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mt-20 sm:mt-24 w-full max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card p-6 sm:p-8 rounded-3xl group hover:border-blue-400 transition-all border border-transparent">

                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Materi Terstruktur</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Penyampaian materi yang sistematis memudahkan siswa memahami setiap kompetensi dasar.</p>
                </div>

                <div class="glass-card p-6 sm:p-8 rounded-3xl group hover:border-purple-400 transition-all border border-transparent">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Evaluasi Real-time</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Sistem penugasan dan ujian online dengan hasil yang dapat dipantau secara langsung.</p>
                </div>

                <div class="glass-card p-6 sm:p-8 rounded-3xl group hover:border-emerald-400 transition-all border border-transparent">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Dashboard Monitoring</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Laporan perkembangan belajar siswa yang komprehensif untuk guru dan orang tua.</p>
                </div>
            </div>
        </main>

        <!-- Elegant Footer -->
        <footer class="mt-auto py-12 px-6">
            <div class="mx-auto max-w-6xl border-t border-gray-200 dark:border-gray-800 pt-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm flex items-center justify-center overflow-hidden shrink-0 animated-logo">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/school_logo_placeholder.svg') }}" alt="School Logo" class="w-full h-full p-2">
                        @endif
                    </div>
                    <span class="text-base sm:text-lg font-bold font-outfit">{{ $school->name }}</span>
                </div>
                <p class="text-sm opacity-50">&copy; {{ date('Y') }} {{ $school->name }}. Semua hak dilindungi.</p>
                <div class="flex gap-6 text-sm opacity-50">
                    <a href="#" class="hover:opacity-100 transition-opacity">Kebijakan Privasi</a>
                    <a href="#" class="hover:opacity-100 transition-opacity">Bantuan</a>
                </div>
            </div>
        </footer>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush

        <script>
            const labels7d = {!! json_encode($labels7d ?? []) !!};
            const siswaActiveCountsByDay = {!! json_encode($siswaActiveCountsByDay ?? []) !!};
            const guruActiveCountsByDay = {!! json_encode($guruActiveCountsByDay ?? []) !!};

            // Render LMS activity charts (only if canvas exists)
            document.addEventListener('DOMContentLoaded', () => {
                const siswaEl = document.getElementById('siswaLmsActivity7d');
                const guruEl = document.getElementById('guruLmsActivity7d');

                if (siswaEl) {
                    new Chart(siswaEl, {
                        type: 'line',
                        data: {
                            labels: labels7d,
                            datasets: [{
                                label: 'Aktivitas Siswa',
                                data: siswaActiveCountsByDay,
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.15)',
                                borderWidth: 2,
                                tension: 0.35,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false }, ticks: { font: { size: 10 } } },
                                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                            }
                        }
                    });
                }

                if (guruEl) {
                    new Chart(guruEl, {
                        type: 'line',
                        data: {
                            labels: labels7d,
                            datasets: [{
                                label: 'Aktivitas Guru',
                                data: guruActiveCountsByDay,
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                                borderWidth: 2,
                                tension: 0.35,
                                fill: true,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#F1F5F9', drawBorder: false }, ticks: { font: { size: 10 } } },
                                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                            }
                        }
                    });
                }

                // Reveal animations
                const observerOptions = { threshold: 0.1 };
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.glass-card').forEach(card => {
                    card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
                    observer.observe(card);
                });

                // Ensure hero content stays visible immediately (avoid layout flash)
                const firstHero = document.querySelector('main .max-w-4xl');
                if (firstHero) {
                    firstHero.classList.remove('opacity-0', 'translate-y-10');
                }
            });

            // (Reveal animations moved into DOMContentLoaded above)
            /*
            const _unused = true;
            */

            /*
            document.addEventListener('DOMContentLoaded', () => {
            */
                const observerOptions = { threshold: 0.1 };
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('opacity-100', 'translate-y-0');
                            entry.target.classList.remove('opacity-0', 'translate-y-10');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.glass-card').forEach(card => {
                    card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
                    observer.observe(card);
                });

                // Ensure hero content stays visible immediately (avoid layout flash)
                const firstHero = document.querySelector('main .max-w-4xl');
                if (firstHero) {
                    firstHero.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        </script>
    </body>
</html>
