<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <x-input-label for="nama_kelas" :value="__('Nama Kelas')" />
                            <x-text-input id="nama_kelas" name="nama_kelas" type="text" class="mt-1 block w-full" :value="old('nama_kelas', $kelas->nama_kelas)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nama_kelas')" />
                        </div>

                        <div>
                            <x-input-label for="wali_kelas" :value="__('Wali Kelas (Opsional)')" />
                            <x-text-input id="wali_kelas" name="wali_kelas" type="text" class="mt-1 block w-full" :value="old('wali_kelas', $kelas->wali_kelas)" />
                            <x-input-error class="mt-2" :messages="$errors->get('wali_kelas')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>
                            <a href="{{ route('admin.kelas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
