<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Monitoring Progres: {{ $mapel->nama_mapel }}
            </h2>
            <a href="{{ route('guru.babs.index', $mapel->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Silabus</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-400 text-sm">
                                <th class="pb-3 pl-2">Nama Siswa</th>
                                <th class="pb-3">Kelas</th>
                                <th class="pb-3">Progres Belajar</th>
                                <th class="pb-3 text-right pr-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($students as $student)
                                <tr>
                                    <td class="py-4 pl-2 font-medium text-gray-900 dark:text-white">{{ $student->name }}</td>
                                    <td class="py-4 text-gray-500 dark:text-gray-400">{{ optional($student->kelas)->nama_kelas ?? '-' }}</td>
                                    <td class="py-4 w-1/3">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                @php
                                                    $color = 'bg-rose-500';
                                                    if($student->progress_percent >= 75) $color = 'bg-emerald-500';
                                                    elseif($student->progress_percent >= 40) $color = 'bg-amber-500';
                                                @endphp
                                                <div class="{{ $color }} h-2 rounded-full transition-all duration-500" style="width: {{ $student->progress_percent }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-black text-gray-700 dark:text-gray-300">{{ number_format((float)$student->progress_percent, 0) }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-right pr-2">
                                        <a href="{{ route('guru.mapel.progress.detail', [$mapel->id, $student->id]) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-bold">Detail Progres</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500 italic">Belum ada siswa yang terdaftar di kelas untuk mata pelajaran ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
