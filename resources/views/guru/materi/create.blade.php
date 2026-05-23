<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Materi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="judul" value="Judul Materi" />
                        <x-text-input id="judul" name="judul" type="text" class="block w-full mt-1" required />
                    </div>
                    <div>
                        <x-input-label for="mapel_id" value="Mata Pelajaran" />
                        <select name="mapel_id" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="bab_id" value="Bab / Topik" />
                        <select name="bab_id" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            @foreach($mapels as $mapel)
                                @foreach($mapel->babs as $bab)
                                    <option value="{{ $bab->id }}" {{ $selectedBab == $bab->id ? 'selected' : '' }}>Bab {{ $bab->urutan }}: {{ $bab->judul }} ({{ $mapel->nama_mapel }})</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="urutan" value="Urutan Materi" />
                        <x-text-input id="urutan" name="urutan" type="number" min="1" value="1" class="block w-full mt-1" required />
                    </div>
                    <div>
                        <x-input-label for="file" value="File Materi (PDF/DOC)" />
                        <input type="file" name="file" class="block w-full mt-1 text-white" required>
                    </div>
                    <x-primary-button>Simpan Materi</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
