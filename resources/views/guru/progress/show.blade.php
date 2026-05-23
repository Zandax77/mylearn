<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Progres: {{ $student->name }}
            </h2>
            <a href="{{ route('guru.mapel.progress', $mapel->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Daftar Siswa</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center space-x-4">
                <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-2xl font-bold">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">Memantau aktivitas pada mata pelajaran: <b>{{ $mapel->nama_mapel }}</b></p>
                </div>
            </div>

            @foreach($progress as $bab)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                        <h4 class="font-bold text-gray-900 dark:text-white">Bab {{ $bab->urutan }}: {{ $bab->judul }}</h4>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Materi Status -->
                        <div>
                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-2">Status Materi</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($bab->materis as $materi)
                                    <div class="flex items-center justify-between p-3 rounded-lg border {{ $materi->is_read ? 'bg-emerald-50 border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-900/50' : 'bg-gray-50 border-gray-100 dark:bg-gray-700/50 dark:border-gray-600' }}">
                                        <span class="text-sm {{ $materi->is_read ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">{{ $materi->judul }}</span>
                                        @if($materi->is_read)
                                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        @else
                                            <span class="text-[10px] bg-gray-200 dark:bg-gray-600 text-gray-500 px-2 py-0.5 rounded">Belum Dibuka</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kuis Status -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-2">Hasil Kuis</h5>
                            @php $kuis = $bab->ujians->where('tipe', 'kuis')->first(); @endphp
                            @if($kuis)
                                @if($kuis->hasil)
                                    <div class="flex items-center justify-between p-4 rounded-xl {{ $kuis->hasil->is_lulus ? 'bg-emerald-600 text-white' : 'bg-red-500 text-white' }}">
                                        <div>
                                            <p class="font-bold">Skor Tertinggi: {{ number_format($kuis->hasil->nilai, 0) }}</p>
                                            <p class="text-xs opacity-80">Diselesaikan pada: {{ $kuis->hasil->completed_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase">
                                            {{ $kuis->hasil->is_lulus ? 'LULUS' : 'GAGAL' }}
                                        </span>
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-xl text-center">
                                        <p class="text-sm text-gray-500 italic">Siswa belum mencoba kuis ini.</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-xs text-gray-400 italic">Tidak ada kuis untuk bab ini.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
