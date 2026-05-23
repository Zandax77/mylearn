<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Bank Soal: {{ $ujian->judul }}
            </h2>
            <a href="{{ route('guru.babs.index', $ujian->mapel_id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Silabus</a>
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
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $ujian->judul }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Sistem akan mengacak <span class="font-bold">{{ $ujian->jumlah_soal_tampil }} soal</span> untuk tiap siswa dengan waktu <span class="font-bold">{{ $ujian->jumlah_soal_tampil }} menit</span>. Passing Grade: <span class="font-bold">{{ $ujian->passing_grade }}%</span>.</p>
                </div>
                @if($ujian->tipe === 'kuis')
                    <a href="{{ route('guru.ujians.soals.create', $ujian->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded transition shadow">
                        + Tambah Soal
                    </a>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                @if($ujian->tipe === 'kuis')
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-4">Daftar Soal Tersedia ({{ $ujian->soals->count() }} Soal)</h4>
                    @if($ujian->soals->count() < $ujian->jumlah_soal_tampil)
                    <div class="mb-4 bg-yellow-50 text-yellow-800 p-3 rounded text-sm border border-yellow-200">
                        <span class="font-bold">Peringatan:</span> Jumlah soal saat ini kurang dari pengaturan jumlah soal tampil ({{ $ujian->jumlah_soal_tampil }}). Kuis tidak akan bisa dimulai oleh siswa. Anda harus menambahkan soal lagi ke dalam bank soal ini.
                    </div>
                    @endif
                    
                    <div class="space-y-4">
                        @forelse($ujian->soals as $index => $soal)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-100 dark:border-gray-600">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 mb-2">{{ $index + 1 }}. {{ $soal->pertanyaan }}</p>
                                    <ul class="text-sm space-y-1 text-gray-600 dark:text-gray-400">
                                        <li class="{{ $soal->jawaban_benar == 'a' ? 'font-bold text-emerald-600 dark:text-emerald-400' : '' }}">A. {{ $soal->opsi_a }}</li>
                                        <li class="{{ $soal->jawaban_benar == 'b' ? 'font-bold text-emerald-600 dark:text-emerald-400' : '' }}">B. {{ $soal->opsi_b }}</li>
                                        <li class="{{ $soal->jawaban_benar == 'c' ? 'font-bold text-emerald-600 dark:text-emerald-400' : '' }}">C. {{ $soal->opsi_c }}</li>
                                        <li class="{{ $soal->jawaban_benar == 'd' ? 'font-bold text-emerald-600 dark:text-emerald-400' : '' }}">D. {{ $soal->opsi_d }}</li>
                                    </ul>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('guru.soals.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 italic py-4">Belum ada soal. Silakan tambah soal baru.</p>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="inline-block p-4 rounded-full bg-emerald-100 dark:bg-emerald-900/50 mb-4">
                            <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Generate Soal Otomatis Aktif</h4>
                        <p class="mt-2 text-gray-500 max-w-lg mx-auto">Karena ini adalah Ujian <b>{{ strtoupper($ujian->tipe) }}</b>, sistem akan secara otomatis mengambil secara acak <span class="font-bold text-gray-900 dark:text-white">{{ $ujian->jumlah_soal_tampil }} soal</span> dari seluruh Bank Soal / Kuis di mapel ini pada saat ujian dimulai oleh siswa.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
