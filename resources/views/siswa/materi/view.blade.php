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
                        <p class="text-xs text-gray-500 mt-1">Tipe: {{ strtoupper($materi->tipe) }}</p>
                    </div>
                </div>

                <div class="p-0 h-[80vh] bg-gray-200 dark:bg-gray-900 overflow-y-auto" style="-webkit-user-select: none; user-select: none;">
                    @if($materi->tipe === 'youtube')
                        @php
                            $youtubeId = '';
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $materi->youtube_url, $match)) {
                                $youtubeId = $match[1];
                            }
                        @endphp
                        @if($youtubeId)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <div class="flex items-center justify-center h-full p-12 text-center text-red-500">
                                Format URL YouTube tidak valid.
                            </div>
                        @endif
                    @elseif($materi->tipe === 'pdf')
                        <div id="pdf-viewer" class="flex flex-col items-center p-4 bg-gray-600 space-y-4 min-h-full">
                            <!-- Canvas elements will be appended here -->
                        </div>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const url = "{{ asset('storage/' . $materi->file) }}";
                                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

                                const loadingTask = pdfjsLib.getDocument(url);
                                loadingTask.promise.then(function(pdf) {
                                    const viewer = document.getElementById('pdf-viewer');
                                    
                                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                                        pdf.getPage(pageNum).then(function(page) {
                                            const scale = 1.5;
                                            const viewport = page.getViewport({scale: scale});
                                            
                                            const canvas = document.createElement('canvas');
                                            const context = canvas.getContext('2d');
                                            canvas.height = viewport.height;
                                            canvas.width = viewport.width;
                                            canvas.className = 'shadow-lg bg-white mb-4';
                                            // Prevent context menu (right-click)
                                            canvas.oncontextmenu = function() { return false; };

                                            viewer.appendChild(canvas);

                                            const renderContext = {
                                                canvasContext: context,
                                                viewport: viewport
                                            };
                                            page.render(renderContext);
                                        });
                                    }
                                }).catch(function(error) {
                                    console.error('Error loading PDF:', error);
                                });
                                
                                // Basic anti-download logic
                                document.addEventListener('contextmenu', event => event.preventDefault());
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
