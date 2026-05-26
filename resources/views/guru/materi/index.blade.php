<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Materi Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-emerald-100 text-emerald-700 p-4 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Materi</h3>
                    <a href="{{ route('guru.materi.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded transition">Tambah Materi</a>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm uppercase tracking-wider">
                                <th class="pb-3 font-semibold">Judul Materi</th>
                                <th class="pb-3 font-semibold">Mata Pelajaran</th>
                                <th class="pb-3 font-semibold">Tipe</th>
                                <th class="pb-3 font-semibold">Tanggal Upload</th>
                                <th class="pb-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($materis as $m)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="py-3 pr-4 text-gray-900 dark:text-gray-100 font-medium">{{ $m->judul }}</td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-300">{{ $m->mapel->nama_mapel }}</td>
                                <td class="py-3 pr-4">
                                    @if(isset($m->tipe) && $m->tipe === 'youtube')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                            YouTube
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                            PDF
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-300 text-sm">{{ $m->tanggal_upload }}</td>
                                <td class="py-3">
                                    <form action="{{ route('guru.materi.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 dark:hover:text-red-400 text-sm font-medium transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400 italic">Belum ada materi yang diunggah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
