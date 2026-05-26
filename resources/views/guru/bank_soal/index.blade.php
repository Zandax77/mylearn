<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Bank Soal: {{ $mapel->nama_mapel }}
            </h2>
            <a href="{{ route('guru.babs.index', $mapel->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Silabus</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Unggah Soal Massal (CSV atau DOCX)</h3>
                    <p class="text-sm text-gray-500 mt-1">Gunakan template untuk mengunggah berbagai variasi soal (Multiple Choice, Benar/Salah, Menjodohkan).</p>
                </div>
                <a href="{{ route('guru.bank_soal.template') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition shadow text-sm">
                    Unduh Template DOCX
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <form action="{{ route('guru.bank_soal.upload', $mapel->id) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-4">
                    @csrf
                    <div class="flex-1">
                        <x-input-label for="file_csv" value="Pilih File CSV atau DOCX" />
                        <input type="file" name="file_csv" id="file_csv" accept=".csv,.docx" class="block w-full mt-1 text-gray-900 dark:text-white" required>
                    </div>
                    <x-primary-button>Unggah ke Bank Soal</x-primary-button>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Daftar Bank Soal ({{ $bankSoals->count() }} Soal)</h4>
                
                <div class="space-y-4">
                    @forelse($bankSoals as $index => $soal)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-100 dark:border-gray-600">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex gap-2 items-center mb-2">
                                    <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded font-bold uppercase">{{ $soal->tipe_soal }}</span>
                                    @if($soal->bab_id)
                                    <span class="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded">Bab {{ $soal->bab->urutan }}</span>
                                    @endif
                                </div>
                                <p class="font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $index + 1 }}. {{ $soal->pertanyaan }}</p>
                                
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-2 bg-gray-200 dark:bg-gray-900 p-3 rounded">
                                    <span class="font-bold">Opsi: </span> {{ json_encode($soal->opsi) }}<br>
                                    <span class="font-bold text-emerald-600">Jawaban: </span> {{ json_encode($soal->jawaban_benar) }}
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <form action="{{ route('guru.bank_soal.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini dari Bank Soal?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline px-2 py-1 bg-red-50 dark:bg-red-900/20 rounded">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 italic py-4">Bank soal masih kosong. Silakan unggah soal menggunakan template CSV atau DOCX.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
