<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('guru.ujians.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded transition shadow">
                    + Buat Ujian Baru
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="w-full text-left table-auto">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-gray-600 dark:text-gray-300">
                            <th class="p-3">Judul</th>
                            <th class="p-3">Mapel</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">Jumlah Soal</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($ujians as $ujian)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="p-3 text-gray-900 dark:text-gray-100 font-medium">{{ $ujian->judul }}</td>
                                <td class="p-3 text-gray-600 dark:text-gray-300">{{ $ujian->mapel->nama_mapel }}</td>
                                <td class="p-3 text-gray-600 dark:text-gray-300 uppercase">{{ $ujian->tipe }}</td>
                                <td class="p-3 text-gray-600 dark:text-gray-300 text-center">{{ $ujian->jumlah_soal_tampil }}</td>
                                <td class="p-3 space-x-2">
                                    <a href="{{ route('guru.ujians.show', $ujian->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200" title="Lihat Detail">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('guru.ujians.edit', $ujian->id) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200" title="Edit">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <form action="{{ route('guru.ujians.destroy', $ujian->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus ujian ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200" title="Hapus">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-400 italic">Belum ada ujian yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
