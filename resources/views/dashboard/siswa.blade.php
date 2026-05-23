<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-3xl font-bold mb-2">Halo, {{ auth()->user()->name }}! 👋</h3>
                        <p class="text-blue-100">Siap untuk belajar hari ini? Mari kita mulai petualangan belajarmu.</p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-24 h-24 text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('siswa.mapel.index') }}" class="group block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full group-hover:scale-110 transition">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 transition">Silabus & Materi</h4>
                            <p class="text-gray-500">Pelajari materi bab demi bab secara berurutan.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('siswa.tugas.index') }}" class="group block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-full group-hover:scale-110 transition">
                            <svg class="w-8 h-8 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100 group-hover:text-amber-600 transition">Tugas & Latihan</h4>
                            <p class="text-gray-500">Kerjakan tugas dan kumpulkan sebelum deadline.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
