<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Silabus: {{ $mapel->nama_mapel }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('guru.mapel.progress', $mapel->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold text-sm hover:bg-emerald-700 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm12-5V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                    Pantau Progres Siswa
                </a>
                <a href="{{ route('guru.mapel.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline flex items-center">&larr; Kembali ke Daftar Mapel</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm">
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Form Tambah Bab -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Tambah Bab Baru</h3>
                    <form action="{{ route('guru.babs.store', $mapel->id) }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <div class="flex-1">
                            <x-input-label for="judul" value="Judul Bab / Topik" />
                            <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="w-32">
                            <x-input-label for="urutan" value="Urutan Ke-" />
                            <x-text-input id="urutan" name="urutan" type="number" min="1" value="{{ $babs->count() + 1 }}" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-primary-button class="h-10">Tambah Bab</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Bab -->
            <div class="space-y-6">
                @forelse($babs as $bab)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-indigo-100 dark:border-indigo-900/50">
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-900/50 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">
                            Bab {{ $bab->urutan }}: {{ $bab->judul }}
                        </h4>
                        <div class="flex gap-2">
                            <form action="{{ route('guru.babs.destroy', $bab->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bab ini beserta seluruh materi dan kuis di dalamnya?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Hapus Bab</button>
                            </form>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <!-- List Materi -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-semibold text-gray-700 dark:text-gray-300">Materi Belajar</h5>
                                <a href="{{ route('guru.materi.create', ['bab_id' => $bab->id]) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">+ Tambah Materi</a>
                            </div>
                            @if($bab->materis->count() > 0)
                                <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    @foreach($bab->materis as $materi)
                                        <li>{{ $materi->judul }} <span class="text-xs text-gray-500">({{ $materi->tanggal_upload }})</span></li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 italic">Belum ada materi di bab ini.</p>
                            @endif
                        </div>

                        <!-- List Kuis / Bank Soal -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h5 class="font-semibold text-gray-700 dark:text-gray-300">Bank Soal & Ujian</h5>
                                <a href="{{ route('guru.ujians.create', ['mapel_id' => $mapel->id, 'bab_id' => $bab->id]) }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">+ Tambah Bank Soal Kuis</a>
                            </div>
                            @if($bab->ujians->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($bab->ujians as $ujian)
                                        <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700/30 p-2 rounded text-sm">
                                            <a href="{{ route('guru.ujians.show', $ujian->id) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">{{ $ujian->judul }}</a>
                                            <div class="flex gap-2 items-center">
                                                <span class="text-xs px-2 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 rounded uppercase">{{ $ujian->tipe }}</span>
                                                <span class="text-xs text-gray-500">{{ $ujian->soals->count() }} Soal Tersedia</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-500 italic">Belum ada kuis/ujian di bab ini.</p>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada Bab/Silabus yang dibuat. Silakan tambahkan di atas.</p>
                </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>
