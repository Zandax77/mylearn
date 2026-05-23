<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    {{ $tugas->judul_tugas }}
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">
                    {{ $tugas->mapel->nama_mapel }}
                </p>
            </div>
            <a href="{{ route('siswa.tugas.show', $tugas->id) }}" class="flex items-center text-xs font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <span class="inline-block px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-indigo-50 text-indigo-600">
                        Lampiran Jawaban
                    </span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mt-2">
                        Tampilkan Jawaban
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Format: {{ strtoupper($extension) }}</p>

                </div>
            </div>

            @if(in_array(strtolower($extension), ['pdf']))
                <div class="h-[80vh] bg-gray-200 dark:bg-gray-900/60 rounded-2xl overflow-hidden">
                    <iframe
                        src="{{ asset('storage/' . $pengumpulan->file_jawaban) }}"
                        class="w-full h-full"
                        frameborder="0"
                        title="Viewer PDF Jawaban">
                    </iframe>
                </div>
            @else
                <div class="flex flex-col items-center justify-center p-12 text-center">
                    <p class="text-gray-700 dark:text-gray-300 font-bold">File tidak didukung untuk viewer langsung.</p>
                    <p class="text-gray-500 mt-2">Anda tetap bisa mengunduh file untuk membukanya.</p>
                    <a
                        href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}"
                        target="_blank"
                        class="mt-6 inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        Unduh untuk Membaca
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

