<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-[#F8FAFC] dark:bg-gray-900 overflow-hidden font-sans antialiased selection:bg-indigo-100 selection:text-indigo-600">

    <style>
        [x-cloak] { display: none !important; }

        /* Samakan breakpoint dengan Tailwind lg (min-width: 1024px) */
        @media (max-width: 1023.98px) {
            .sidebar-desktop { display: none !important; }
            .hamburger-mobile { display: flex !important; }
            .header-desktop { display: none !important; }
            .header-mobile { display: block !important; }
        }

        @media (min-width: 1024px) {
            .sidebar-desktop { display: flex !important; }
            .hamburger-mobile { display: none !important; }
            .sidebar-mobile-overlay { display: none !important; }
            .header-desktop { display: block !important; }
            .header-mobile { display: none !important; }
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }

        @keyframes gentle-float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-4px) rotate(1.5deg);
            }
        }

        .animated-logo {
            animation: gentle-float 3s ease-in-out infinite;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .animated-logo:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.2);
        }
    </style>

    <!-- DESKTOP SIDEBAR (COMPACT) -->
    <aside class="sidebar-desktop flex-col w-72 shrink-0 bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 relative z-30 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="flex flex-col items-center justify-center h-48 px-4 py-5 border-b border-gray-50 dark:border-gray-700 shrink-0 gap-2.5">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-3 group">
                <div class="w-32 h-32 rounded-3xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shadow-xl flex items-center justify-center overflow-hidden shrink-0 animated-logo">
                    @if($school->logo)
                        <img src="{{ asset('storage/' . $school->logo) }}" class="w-full h-full object-contain p-1.5">
                    @else
                        <x-application-logo class="h-16 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
                    @endif
                </div>
                <div class="flex flex-col items-center min-w-0 w-full">
                    <span class="text-[10px] font-black text-gray-900 dark:text-white uppercase truncate tracking-tight text-center w-full">{{ $school->name }}</span>
                    <span class="text-[7px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-[0.2em] mt-0.5 font-mono">LMS Hub</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto px-5 py-6 space-y-1 custom-scrollbar">
            <h4 class="px-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Navigasi</h4>

            {{-- DASHBOARD --}}
            <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6">Dashboard</x-nav-link-sidebar>

            {{-- ADMIN --}}
            @if(Auth::user()->role === 'admin')
                <h4 class="px-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mt-8 mb-4">Administrasi</h4>
                <x-nav-link-sidebar :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">Data Kelas</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.mapel.index')" :active="request()->routeIs('admin.mapel.*')" icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">Mapel</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">Data User</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.monitoring.guru')" :active="request()->routeIs('admin.monitoring.*')" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12-5V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z">Monitor Guru</x-nav-link-sidebar>

                <h4 class="px-4 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mt-8 mb-4">Sistem</h4>
                <x-nav-link-sidebar :href="route('admin.import.index')" :active="request()->routeIs('admin.import.*')" icon="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">Import</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">Sekolah</x-nav-link-sidebar>
            @endif
        </nav>


        <div class="p-6 border-t border-gray-50 dark:border-gray-700 bg-gray-50/30">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white text-xs font-black shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] font-black text-gray-900 dark:text-white truncate uppercase tracking-tight">{{ Auth::user()->name }}</p>
                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MOBILE OVERLAY -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="sidebar-mobile-overlay fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm"></div>

    <!-- MOBILE SIDEBAR -->
    <aside x-show="sidebarOpen" x-cloak class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 flex flex-col shadow-2xl lg:hidden">
        <div class="flex items-center justify-between h-16 px-6 border-b dark:border-gray-700">
            <span class="text-[10px] font-black uppercase tracking-tighter">{{ $school->name }}</span>
            <button @click="sidebarOpen = false" class="p-2 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <nav class="flex-1 overflow-y-auto px-4 py-6">
            <h4 class="px-1 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-4">Navigasi</h4>

            <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m10-11 2 2m-2-2v10a1 1 0 0 1-1 1h-3m-6 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6">Dashboard</x-nav-link-sidebar>

            @if(Auth::user()->role === 'admin')
                <h4 class="px-1 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mt-8 mb-4">Administrasi</h4>
                <x-nav-link-sidebar :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.*')" icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">Data Kelas</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.mapel.index')" :active="request()->routeIs('admin.mapel.*')" icon="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">Mapel</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">Data User</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.monitoring.guru')" :active="request()->routeIs('admin.monitoring.*')" icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12-5V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z">Monitor Guru</x-nav-link-sidebar>

                <h4 class="px-1 text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mt-8 mb-4">Sistem</h4>
                <x-nav-link-sidebar :href="route('admin.import.index')" :active="request()->routeIs('admin.import.*')" icon="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">Import</x-nav-link-sidebar>
                <x-nav-link-sidebar :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">Sekolah</x-nav-link-sidebar>
            @endif
        </nav>

    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <header class="flex items-center justify-between px-8 lg:px-10 h-20 bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-b border-gray-100 dark:border-gray-700 shrink-0 relative z-20">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="hamburger-mobile p-2 -ml-2 text-gray-400 hover:text-indigo-600 lg:hidden">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                </button>
                @isset($header)
                    <div class="header-desktop">{{ $header }}</div>
                @endisset
            </div>
            <div class="flex items-center space-x-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 bg-gray-50 text-gray-600 text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-gray-100">
                        <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto custom-scrollbar">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 py-6 md:py-10">

                @isset($header)
                    <div class="header-mobile mb-8">{{ $header }}</div>
                @endisset
                {{ $slot }}
            </div>
        </main>
    </div>
</div>
