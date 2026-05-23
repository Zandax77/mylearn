<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Atur Kelas untuk: {{ $mapel->nama_mapel }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-400 mb-6 text-sm">Pilih kelas mana saja yang mengikuti mata pelajaran ini. Siswa di kelas yang dicentang akan dapat melihat materi dan tugas untuk mapel ini.</p>
                    
                    <form action="{{ route('guru.mapel.update', $mapel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @forelse($kelas as $k)
                            <div class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <input id="kelas_{{ $k->id }}" type="checkbox" name="kelas_id[]" value="{{ $k->id }}" class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer" {{ in_array($k->id, $assignedKelas) ? 'checked' : '' }}>
                                <label for="kelas_{{ $k->id }}" class="ml-3 w-full text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">{{ $k->nama_kelas }}</label>
                            </div>
                            @empty
                            <p class="text-gray-500">Tidak ada data kelas tersedia.</p>
                            @endforelse
                        </div>

                        <div class="flex gap-4 border-t dark:border-gray-700 pt-4 mt-6">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">Simpan Pengaturan Kelas</x-primary-button>
                            <a href="{{ route('guru.mapel.index') }}" class="px-4 py-2 text-gray-400 hover:text-white transition">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
