<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 4s;"></div>

            <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-white/10 dark:bg-gray-900/40 backdrop-blur-xl border border-white/20 shadow-2xl overflow-hidden sm:rounded-2xl">
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex flex-col items-center group">
                        <div class="bg-gradient-to-tr from-indigo-500 to-purple-500 p-4 rounded-2xl shadow-lg transform transition duration-300 group-hover:scale-110 group-hover:rotate-3">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="mt-4 text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-200 to-white tracking-wider">MyLearn LMS</span>
                    </a>
                </div>

                {{ $slot }}
            </div>
            
            <div class="relative z-10 mt-8 text-center text-white/50 text-sm">
                &copy; {{ date('Y') }} SMK Bisa. All rights reserved.
            </div>
        </div>
    </body>
</html>
