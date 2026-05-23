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
                <div class="p-6 border-b border-gray-200 flex justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Materi</h3>
                    <a href="{{ route('guru.materi.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Materi</a>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                <th class="pb-3">Judul Materi</th>
                                <th class="pb-3">Mata Pelajaran</th>
                                <th class="pb-3">Tanggal Upload</th>
                                <th class="pb-3">File</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materis as $m)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-3 text-white">{{ $m->judul }}</td>
                                <td class="py-3 text-white">{{ $m->mapel->nama_mapel }}</td>
                                <td class="py-3 text-white">{{ $m->tanggal_upload }}</td>
                                <td class="py-3"><a href="{{ Storage::url($m->file) }}" target="_blank" class="text-indigo-400">Download</a></td>
                                <td class="py-3 text-white">
                                    <form action="{{ route('guru.materi.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-4 text-center text-gray-500">Belum ada materi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
