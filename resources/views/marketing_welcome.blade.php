<!DOCTYPE html>
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

            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }

            .btn-primary {
                background: var(--primary);
                color: var(--bg-soft);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        </style>
    </head>

    <body class="antialiased hero-gradient min-h-screen flex flex-col">
        <!-- Minimalist Navigation -->
        <header class="fixed top-0 w-full z-50 px-6 py-4">
            <nav class="mx-auto max-w-6xl glass-card rounded-2xl px-6 py-3 flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center overflow-hidden">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/school_logo_placeholder.svg') }}" alt="School Logo" class="w-full h-full p-2">
                        @endif
                    </div>
                    <span class="text-lg font-semibold tracking-tight font-outfit">{{ $school->name }}</span>
                </a>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary px-5 py-2 rounded-xl text-sm font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium nav-link">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary px-5 py-2 rounded-xl text-sm font-medium">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col items-center justify-center pt-32 pb-20 px-6">
            <div class="max-w-4xl w-full text-center">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-card text-xs font-medium mb-8 animate-float">
                    <span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></span>
                    <span>Platform Belajar Terintegrasi</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-6 bg-clip-text text-transparent bg-gradient-to-b from-current to-gray-500 leading-tight">
                    Belajar Lebih Elegan <br class="hidden md:block"> Bersama {{ $school->name }}
                </h1>

                <p class="text-lg md:text-xl opacity-70 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Pengalaman belajar yang minimalist, terstruktur, dan fokus pada hasil. Kelola materi, tugas, dan evaluasi dalam satu genggaman.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-lg shadow-xl">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-lg shadow-xl w-full sm:w-auto text-center">
                            Mulai Sekarang
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary px-8 py-4 rounded-2xl font-semibold text-lg w-full sm:w-auto text-center">
                                Registrasi Siswa
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Visual Preview (Abstract) -->
            <div class="mt-24 w-full max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-card p-8 rounded-3xl group hover:border-blue-400 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Materi Terstruktur</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Susunan materi belajar yang rapi per kategori dan tingkat kesulitan.</p>
                </div>

                <div class="glass-card p-8 rounded-3xl group hover:border-purple-400 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Evaluasi Cerdas</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Sistem ujian dan penugasan dengan feedback instan untuk siswa.</p>
                </div>

                <div class="glass-card p-8 rounded-3xl group hover:border-emerald-400 transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 font-outfit">Monitoring Progress</h3>
                    <p class="text-sm opacity-60 leading-relaxed">Pantau perkembangan akademik siswa secara real-time dan akurat.</p>
                </div>
            </div>
        </main>

        <!-- Elegant Footer -->
        <footer class="mt-auto py-12 px-6">
            <div class="mx-auto max-w-6xl border-t border-gray-200 dark:border-gray-800 pt-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center overflow-hidden">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/school_logo_placeholder.svg') }}" alt="School Logo" class="w-full h-full p-1.5">
                        @endif
                    </div>
                    <span class="font-semibold font-outfit">{{ $school->name }}</span>
                </div>
                <p class="text-sm opacity-50">&copy; {{ date('Y') }} {{ $school->name }}. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="text-sm opacity-50 hover:opacity-100 transition-opacity">Kebijakan Privasi</a>
                    <a href="#" class="text-sm opacity-50 hover:opacity-100 transition-opacity">Kontak Kami</a>
                </div>
            </div>
        </footer>

        <script>
            // Simple animation on scroll
            document.addEventListener('DOMContentLoaded', () => {
                const cards = document.querySelectorAll('.glass-card');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });

                cards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    observer.observe(card);
                });
            });
        </script>
    </body>
</html>
