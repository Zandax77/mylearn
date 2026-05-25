<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Import Data Massal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            @if(session('warning'))
            <div class="bg-amber-100 border-l-4 border-amber-500 text-amber-700 p-4 rounded shadow-sm">
                <p>{{ session('warning') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card Import Pengguna -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Import Guru & Siswa</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Format CSV harus memiliki baris pertama (header) persis seperti berikut:<br>
                            <code class="block mt-2 bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded text-pink-500 font-mono text-sm border border-gray-200 dark:border-gray-600">name,email,role,nama_kelas</code>
                        </p>
                        <ul class="text-xs text-gray-500 dark:text-gray-400 mb-4 list-disc list-inside">
                            <li>Role harus diisi dengan "guru" atau "siswa".</li>
                            <li>Akun guru dan siswa otomatis langsung aktif.</li>
                            <li>Password default diatur secara otomatis ke <strong class="text-indigo-600 dark:text-indigo-400 font-semibold font-mono">password</strong> dan dapat diubah kemudian secara mandiri oleh pemilik akun.</li>
                            <li>Kolom nama_kelas opsional, khusus untuk siswa. Jika kelas belum ada, sistem akan membuatnya otomatis.</li>
                        </ul>
                        <div class="mb-6">
                            <a href="{{ route('admin.import.template.users') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template Pengguna (CSV)
                            </a>
                        </div>
                        <form action="{{ route('admin.import.users') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                <input type="file" name="file_users" accept=".csv" required
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 transition cursor-pointer">
                            </div>
                            <x-primary-button class="w-full justify-center py-3 shadow-lg hover:-translate-y-0.5 transition-transform">Mulai Import Pengguna</x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Card Import Kelas -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="bg-emerald-100 dark:bg-emerald-900 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Import Data Kelas</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Format CSV harus memiliki baris pertama (header) persis seperti berikut:<br>
                            <code class="block mt-2 bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded text-pink-500 font-mono text-sm border border-gray-200 dark:border-gray-600">nama_kelas,wali_kelas</code>
                        </p>
                        <ul class="text-xs text-gray-500 dark:text-gray-400 mb-4 list-disc list-inside">
                            <li>Wali kelas opsional, bisa dikosongkan.</li>
                            <li>Sistem akan mengabaikan data jika nama kelas sudah ada.</li>
                        </ul>
                        <div class="mb-6">
                            <a href="{{ route('admin.import.template.kelas') }}" class="inline-flex items-center text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template Kelas (CSV)
                            </a>
                        </div>
                        <form action="{{ route('admin.import.kelas') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                <input type="file" name="file_kelas" accept=".csv" required
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-emerald-50 file:text-emerald-700
                                    hover:file:bg-emerald-100 dark:file:bg-emerald-900 dark:file:text-emerald-300 transition cursor-pointer">
                            </div>
                            <x-primary-button class="w-full justify-center py-3 bg-emerald-600 hover:bg-emerald-700 shadow-lg hover:-translate-y-0.5 transition-transform">Mulai Import Kelas</x-primary-button>
                        </form>
                    </div>
                </div>

                <!-- Card Import Mapel -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full mr-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747 0-6.002-4.5-10.747-10-10.747z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Import Mata Pelajaran</h3>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Format CSV harus memiliki baris pertama (header) persis seperti berikut:<br>
                            <code class="block mt-2 bg-gray-100 dark:bg-gray-700 px-3 py-2 rounded text-pink-500 font-mono text-sm border border-gray-200 dark:border-gray-600">nama_mapel,guru_email</code>
                        </p>
                        <ul class="text-xs text-gray-500 dark:text-gray-400 mb-4 list-disc list-inside">
                            <li>Nama mapel tidak boleh duplikat dengan yang sudah ada.</li>
                            <li>Guru_email opsional. Jika diisi, harus sesuai dengan email guru yang sudah terdaftar.</li>
                            <li>Jika guru tidak ditemukan, mapel tetap akan dibuat tanpa guru.</li>
                        </ul>
                        <div class="mb-6">
                            <a href="{{ route('admin.import.template.mapel') }}" class="inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Template Mapel (CSV)
                            </a>
                        </div>
                        <form action="{{ route('admin.import.mapel') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer">
                                <input type="file" name="file_mapel" accept=".csv" required
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-purple-50 file:text-purple-700
                                    hover:file:bg-purple-100 dark:file:bg-purple-900 dark:file:text-purple-300 transition cursor-pointer">
                            </div>
                            <x-primary-button class="w-full justify-center py-3 bg-purple-600 hover:bg-purple-700 shadow-lg hover:-translate-y-0.5 transition-transform">Mulai Import Mapel</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
