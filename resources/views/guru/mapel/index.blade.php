<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Mata Pelajaran Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-emerald-100 text-emerald-700 p-4 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Mata Pelajaran & Kelas</h3>
                </div>
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                <th class="pb-3">Nama Mata Pelajaran</th>
                                <th class="pb-3">Diajarkan di Kelas</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mapels as $mapel)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-4 text-gray-900 dark:text-white font-bold">{{ $mapel->nama_mapel }}</td>
                                <td class="py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($mapel->kelas as $k)
                                            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">{{ $k->nama_kelas }}</span>
                                        @empty
                                            <span class="text-gray-500 text-sm italic">Belum ada kelas yang di-assign.</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('guru.babs.index', $mapel->id) }}" class="text-indigo-400 hover:text-indigo-300 font-medium bg-indigo-900/30 px-3 py-1.5 rounded transition shadow-sm">Silabus / Bab</a>
                                        <a href="{{ route('guru.mapel.progress', $mapel->id) }}" class="text-amber-400 hover:text-amber-300 font-medium bg-amber-900/30 px-3 py-1.5 rounded transition shadow-sm">Monitoring</a>
                                        <a href="{{ route('guru.mapel.assign', $mapel->id) }}" class="text-emerald-400 hover:text-emerald-300 font-medium bg-emerald-900/30 px-3 py-1.5 rounded transition shadow-sm">Atur Kelas</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">Belum ada mata pelajaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
