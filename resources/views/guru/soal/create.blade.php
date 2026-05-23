<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Tambah Soal ke Bank: {{ $ujian->judul }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form action="{{ route('guru.ujians.soals.store', $ujian->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="pertanyaan" value="Pertanyaan" />
                        <textarea id="pertanyaan" name="pertanyaan" rows="3" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="opsi_a" value="Opsi A" />
                            <x-text-input id="opsi_a" name="opsi_a" type="text" class="block w-full mt-1" required />
                        </div>
                        <div>
                            <x-input-label for="opsi_b" value="Opsi B" />
                            <x-text-input id="opsi_b" name="opsi_b" type="text" class="block w-full mt-1" required />
                        </div>
                        <div>
                            <x-input-label for="opsi_c" value="Opsi C" />
                            <x-text-input id="opsi_c" name="opsi_c" type="text" class="block w-full mt-1" required />
                        </div>
                        <div>
                            <x-input-label for="opsi_d" value="Opsi D" />
                            <x-text-input id="opsi_d" name="opsi_d" type="text" class="block w-full mt-1" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="jawaban_benar" value="Kunci Jawaban Benar" />
                        <select name="jawaban_benar" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex gap-4">
                        <x-primary-button>Simpan Soal</x-primary-button>
                        <a href="{{ route('guru.ujians.show', $ujian->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
