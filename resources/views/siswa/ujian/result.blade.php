<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hasil Ujian: {{ $ujian->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-center">
                    <div class="inline-block p-4 rounded-full {{ $isLulus ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} mb-6">
                        @if($isLulus)
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @else
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @endif
                    </div>

                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                        {{ $isLulus ? 'Selamat, Anda Lulus!' : 'Maaf, Anda Belum Lulus' }}
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">
                        {{ $isLulus ? 'Anda telah berhasil menyelesaikan kuis bab ini dengan predikat baik.' : 'Anda memerlukan minimal nilai 80 untuk lulus bab ini.' }}
                    </p>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 grid grid-cols-2 gap-4 mb-8 border border-gray-100 dark:border-gray-600">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Skor Akhir</p>
                            <p class="text-4xl font-black {{ $isLulus ? 'text-emerald-600' : 'text-red-600' }}">{{ number_format($nilai, 0) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Benar</p>
                            <p class="text-4xl font-black text-gray-800 dark:text-white">{{ $jumlahBenar }}/{{ $soals->count() }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-3">
                        @if($isLulus)
                            <a href="{{ route('siswa.mapel.show', $ujian->mapel_id) }}" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                Lanjutkan ke Bab Berikutnya
                            </a>
                        @else
                            <a href="{{ route('siswa.ujian.show', $ujian->id) }}" class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">
                                Coba Kuis Lagi
                            </a>
                            <a href="{{ route('siswa.mapel.show', $ujian->mapel_id) }}" class="w-full py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-bold hover:bg-gray-50 transition">
                                Pelajari Materi Kembali
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
