<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Monitoring Ketuntasan LMS (Admin)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-white">
                    <h3 class="text-lg font-bold mb-4">Penggunaan LMS oleh Guru</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-400 text-sm">
                                    <th class="pb-3 pl-2">Guru</th>
                                    <th class="pb-3">Mata Pelajaran</th>
                                    <th class="pb-3 text-center">Bab</th>
                                    <th class="pb-3 text-center">Materi</th>
                                    <th class="pb-3 text-center">Kuis</th>
                                    <th class="pb-3">Ketuntasan Persiapan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-gray-700">
                                @forelse($gurus as $guru)
                                    @php $rowspan = $guru->mapels->count() > 0 ? $guru->mapels->count() : 1; @endphp
                                    @forelse($guru->mapels as $index => $mapel)
                                        <tr>
                                            @if($index === 0)
                                                <td class="py-4 pl-2 font-medium" rowspan="{{ $rowspan }}">
                                                    {{ $guru->name }}
                                                    <p class="text-[10px] text-gray-500 font-normal">{{ $guru->email }}</p>
                                                </td>
                                            @endif
                                            <td class="py-4 text-gray-800 dark:text-gray-200">{{ $mapel->nama_mapel }}</td>
                                            <td class="py-4 text-center">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded text-xs font-bold">{{ $mapel->bab_count }}</span>
                                            </td>
                                            <td class="py-4 text-center">
                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded text-xs font-bold">{{ $mapel->materi_count }}</span>
                                            </td>
                                            <td class="py-4 text-center">
                                                <span class="px-2 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded text-xs font-bold">{{ $mapel->kuis_count }}</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                                        @php
                                                            $color = 'bg-rose-500';
                                                            if($mapel->completeness >= 75) $color = 'bg-emerald-500';
                                                            elseif($mapel->completeness >= 40) $color = 'bg-amber-500';
                                                        @endphp
                                                        <div class="h-2 rounded-full {{ $color }}" style="width: {{ $mapel->completeness }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-bold">{{ $mapel->completeness }}%</span>
                                                </div>
                                                <p class="text-[9px] mt-1 text-gray-400 italic">
                                                    @if($mapel->completeness < 100)
                                                        Perlu: {{ $mapel->bab_count == 0 ? 'Bab, ' : '' }}{{ $mapel->materi_count == 0 ? 'Materi, ' : '' }}{{ $mapel->kuis_count == 0 ? 'Kuis' : '' }}
                                                    @else
                                                        Siap Digunakan
                                                    @endif
                                                </p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="py-4 pl-2 font-medium">{{ $guru->name }}</td>
                                            <td colspan="5" class="py-4 text-center text-gray-500 italic text-sm">Belum mengelola mata pelajaran apapun.</td>
                                        </tr>
                                    @endforelse
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500">Belum ada data guru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
