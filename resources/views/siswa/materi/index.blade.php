<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Materi Pelajaran</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($materis as $m)
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow flex flex-col justify-between border-l-4 border-blue-500">
                    <div>
                        <span class="text-xs font-semibold text-blue-500 bg-blue-100 px-2 py-1 rounded">{{ $m->mapel->nama_mapel }}</span>
                        <h3 class="mt-2 text-xl font-bold text-white">{{ $m->judul }}</h3>
                        <p class="text-gray-400 text-sm mt-1">Oleh: {{ $m->mapel->guru->name }} | {{ $m->tanggal_upload }}</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ Storage::url($m->file) }}" target="_blank" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Download Materi</a>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center text-gray-500 py-8 bg-white dark:bg-gray-800 rounded shadow">Belum ada materi tersedia.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
