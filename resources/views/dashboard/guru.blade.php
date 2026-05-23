<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-indigo-100 dark:border-indigo-900">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }}</h3>
                            <p class="text-gray-500">Anda memiliki <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $tugasAktif }}</span> tugas aktif yang perlu diperhatikan.</p>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('guru.materi.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Kelola Materi</a>
                        <a href="{{ route('guru.tugas.index') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition">Kelola Tugas</a>
                        <a href="{{ route('guru.mapel.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition shadow">Atur Kelas</a>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Progres Siswa -->
            <div class="mt-8">
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Ringkasan Progres Siswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($mapels as $mapel)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $mapel->nama_mapel }}</h4>
                                    <p class="text-xs text-gray-500">{{ $mapel->kelas->pluck('nama_kelas')->join(', ') }}</p>
                                </div>
                                <div class="bg-indigo-100 dark:bg-indigo-900 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                            </div>
                            
                            @php
                                $totalSiswa = $mapel->kelas->flatMap->siswa->count();
                            @endphp
                            
                            <div class="flex items-center justify-between text-sm mb-4">
                                <span class="text-gray-500">Total Siswa:</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $totalSiswa }}</span>
                            </div>

                            <a href="{{ route('guru.mapel.progress', $mapel->id) }}" class="block text-center w-full py-2 bg-gray-50 dark:bg-gray-700/50 text-indigo-600 dark:text-indigo-400 text-sm font-bold rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                Lihat Progres Belajar Siswa
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
