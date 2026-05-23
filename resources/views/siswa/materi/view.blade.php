<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Membaca: {{ $materi->judul }}
            </h2>
            <a href="{{ route('siswa.mapel.show', $materi->mapel_id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Silabus</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $materi->judul }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Format: {{ strtoupper($extension) }}</p>
                    </div>
                    <a href="{{ route('siswa.materi.download', $materi->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Unduh File
                    </a>
                </div>

                <div class="p-0 h-[80vh] bg-gray-200 dark:bg-gray-900">
                    @if(in_array(strtolower($extension), ['pdf']))
                        <iframe src="{{ asset('storage/' . $materi->file) }}" class="w-full h-full" frameborder="0"></iframe>
                    @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <div class="flex items-center justify-center h-full p-4">
                            <img src="{{ asset('storage/' . $materi->file) }}" class="max-w-full max-h-full rounded shadow-lg">
                        </div>
                    @elseif(in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                        <div class="flex items-center justify-center h-full">
                            <video controls class="max-w-full max-h-full">
                                <source src="{{ asset('storage/' . $materi->file) }}" type="video/{{ $extension }}">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full p-12 text-center">
                            <svg class="w-20 h-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <h4 class="text-xl font-bold text-gray-700 dark:text-gray-300">File tidak dapat ditampilkan langsung</h4>
                            <p class="text-gray-500 mt-2">Format file ini ({{ $extension }}) memerlukan aplikasi eksternal untuk dibuka.</p>
                            <a href="{{ route('siswa.materi.download', $materi->id) }}" class="mt-6 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">Unduh untuk Membaca</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
