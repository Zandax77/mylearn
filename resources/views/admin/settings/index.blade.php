<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Pengaturan Identitas Sekolah
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 text-emerald-700 p-4 rounded-lg shadow-sm border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf
                    
                    <div>
                        <x-input-label for="name" value="Nama Sekolah / Institusi" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $setting->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="logo" value="Logo Sekolah" />
                        <div class="mt-2 flex items-center space-x-6">
                            <div class="shrink-0">
                                @if($setting->logo)
                                    <img class="h-24 w-24 object-contain rounded-lg border p-1" src="{{ asset('storage/' . $setting->logo) }}" alt="Current school logo">
                                @else
                                    <div class="h-24 w-24 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <label class="block">
                                <span class="sr-only">Pilih Logo</span>
                                <input type="file" name="logo" id="logo" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100
                                    dark:file:bg-gray-700 dark:file:text-gray-300
                                "/>
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Format: PNG, JPG, SVG. Maksimal 2MB.</p>
                        <x-input-error class="mt-2" :messages="$errors->get('logo')" />
                    </div>

                    <div class="flex items-center justify-end pt-4 border-t dark:border-gray-700">
                        <x-primary-button>
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
