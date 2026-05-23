<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Mata Pelajaran Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        
                        <div>
                            <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
                            <x-text-input id="nama_mapel" name="nama_mapel" type="text" class="mt-1 block w-full" :value="old('nama_mapel')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_mapel')" />
                        </div>

                        <div>
                            <x-input-label for="guru_id" :value="__('Guru Pengampu')" />
                            <select id="guru_id" name="guru_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Pilih Guru --</option>
                                @foreach($gurus as $guru)
                                    <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('guru_id')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">{{ __('Simpan') }}</x-primary-button>
                            <a href="{{ route('admin.mapel.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
