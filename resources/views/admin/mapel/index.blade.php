<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Mata Pelajaran</h3>
                    <a href="{{ route('admin.mapel.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition shadow-sm">
                        + Tambah Mapel
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                                <th class="px-6 py-4 font-medium text-sm">ID</th>
                                <th class="px-6 py-4 font-medium text-sm">Nama Mapel</th>
                                <th class="px-6 py-4 font-medium text-sm">Guru Pengampu</th>
                                <th class="px-6 py-4 font-medium text-sm text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($mapels as $mapel)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $mapel->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $mapel->nama_mapel }}</td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $mapel->guru ? $mapel->guru->name : '-' }}</td>
                                <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                    <a href="{{ route('admin.mapel.edit', $mapel->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mapel ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data mata pelajaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
