<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $mapel->nama_mapel }} - Silabus Pembelajaran
            </h2>
            <a href="{{ route('siswa.mapel.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Daftar Mapel</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @foreach($babs as $bab)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border {{ $bab->is_locked ? 'border-gray-200 dark:border-gray-700 opacity-75' : 'border-indigo-200 dark:border-indigo-900 shadow-md' }}">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold {{ $bab->is_locked ? 'text-gray-500' : 'text-gray-900 dark:text-white' }}">
                                    Bab {{ $bab->urutan }}: {{ $bab->judul }}
                                </h3>
                                @if($bab->is_locked)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                        Terkunci - Selesaikan Bab Sebelumnya
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <!-- Materi -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">Materi Belajar</h4>
                                @forelse($bab->materis as $materi)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600 mb-2">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="{{ $bab->is_locked ? 'text-gray-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $materi->judul }}</span>
                                        </div>
                                        @if(!$bab->is_locked)
                                            <a href="{{ route('siswa.materi.view', $materi->id) }}" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400 rounded-md text-xs font-bold hover:bg-indigo-600 hover:text-white transition">Buka Materi</a>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400 italic">Belum ada materi.</p>
                                @endforelse
                            </div>

                            <!-- Kuis -->
                            <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                                @php
                                    $kuis = $bab->ujians->where('tipe', 'kuis')->first();
                                @endphp

                                @if($kuis)
                                    <div class="p-4 rounded-xl {{ $bab->has_passed_kuis ? 'bg-emerald-50 dark:bg-emerald-900/20' : ($bab->all_materials_read ? 'bg-amber-50 dark:bg-amber-900/20 animate-pulse border border-amber-200' : 'bg-gray-50 dark:bg-gray-700/50') }}">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-bold {{ $bab->has_passed_kuis ? 'text-emerald-700 dark:text-emerald-400' : ($bab->all_materials_read ? 'text-amber-700 dark:text-amber-400' : 'text-gray-600 dark:text-gray-400') }}">
                                                    Kuis Bab {{ $bab->urutan }}: {{ $kuis->judul }}
                                                </h4>
                                                @if($bab->has_passed_kuis)
                                                    <p class="text-xs text-emerald-600 mt-1 font-semibold">✓ Anda telah lulus bab ini.</p>
                                                @elseif($bab->all_materials_read)
                                                    <p class="text-xs text-amber-600 mt-1 font-bold">⚠️ Materi Selesai! Segera kerjakan kuis untuk lanjut ke bab berikutnya.</p>
                                                @else
                                                    <p class="text-xs text-gray-400 mt-1">Selesaikan semua materi untuk membuka kuis.</p>
                                                @endif
                                            </div>
                                            
                                            @if(!$bab->is_locked)
                                                @if($bab->all_materials_read || $bab->has_passed_kuis)
                                                    <a href="{{ route('siswa.ujian.show', $kuis->id) }}" 
                                                       onclick="return confirm('Apakah Anda siap mengerjakan kuis? \n- Jumlah Soal: {{ $kuis->jumlah_soal_tampil }} \n- Waktu: {{ $kuis->jumlah_soal_tampil }} menit \n- Syarat Lulus: 80% \n\nKlik OK untuk memulai.')"
                                                       class="inline-flex items-center px-4 py-2 {{ $bab->has_passed_kuis ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-500 text-white shadow-lg shadow-amber-200' }} border border-transparent rounded-lg font-bold text-xs uppercase tracking-widest hover:scale-105 transition transform">
                                                        {{ $bab->has_passed_kuis ? 'Ulang Kuis' : 'Mulai Kuis' }}
                                                    </a>
                                                @else
                                                    <button disabled class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-400 border border-transparent rounded-lg font-bold text-xs uppercase tracking-widest cursor-not-allowed">
                                                        Kuis Terkunci
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 italic">Bab ini tidak memiliki kuis wajib.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <!-- UTS & UAS Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-12">
                @php
                    $uts = $mapel->ujians->where('tipe', 'uts')->first();
                    $uas = $mapel->ujians->where('tipe', 'uas')->first();
                @endphp

                @if($uts)
                    <div class="bg-indigo-600 p-6 rounded-xl text-white shadow-lg">
                        <h3 class="text-xl font-bold mb-2">Uji Tengah Semester</h3>
                        <p class="text-indigo-100 text-sm mb-6">Mencakup materi Bab 1 hingga pertengahan silabus.</p>
                        <a href="{{ route('siswa.ujian.show', $uts->id) }}" class="inline-block bg-white text-indigo-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-indigo-50 transition">Ikuti UTS</a>
                    </div>
                @endif

                @if($uas)
                    <div class="bg-purple-600 p-6 rounded-xl text-white shadow-lg">
                        <h3 class="text-xl font-bold mb-2">Uji Akhir Semester</h3>
                        <p class="text-purple-100 text-sm mb-6">Ujian akhir mencakup seluruh materi dalam satu semester.</p>
                        <a href="{{ route('siswa.ujian.show', $uas->id) }}" class="inline-block bg-white text-purple-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-purple-50 transition">Ikuti UAS</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
