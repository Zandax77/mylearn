<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    {{ $tugas->judul_tugas }}
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">{{ $tugas->mapel->nama_mapel }}</p>
            </div>
            <a href="{{ route('siswa.tugas.index') }}" class="flex items-center text-xs font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="fixed top-24 right-8 z-[100]">
                <div class="bg-white dark:bg-gray-800 border-l-4 border-emerald-500 shadow-2xl rounded-2xl p-5 flex items-center min-w-[320px]">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0 mr-4">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto p-1 text-gray-300 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Info Tugas --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <span class="inline-block px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-amber-50 text-amber-600 mb-3">
                        {{ $tugas->mapel->nama_mapel }}
                    </span>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white">{{ $tugas->judul_tugas }}</h3>
                </div>
                <div class="text-right shrink-0 ml-4">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Deadline</p>
                    <p class="text-sm font-bold {{ $tugas->deadline < now() ? 'text-rose-600' : 'text-gray-900 dark:text-white' }} mt-0.5">
                        {{ $tugas->deadline->format('d M Y, H:i') }}
                    </p>
                    @if($tugas->deadline < now())
                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest">Sudah Lewat</span>
                    @endif
                </div>
            </div>

            @if($tugas->deskripsi)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-5 mb-6">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Deskripsi Tugas</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $tugas->deskripsi }}</p>
                </div>
            @endif

            @if($tugas->file_tugas)
                <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank"
                   class="inline-flex items-center px-5 py-3 bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-100 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Lampiran Tugas
                </a>
            @endif
        </div>

        {{-- Status Pengumpulan --}}
        @if($pengumpulan)
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Sudah Dikumpulkan</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">{{ $pengumpulan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($pengumpulan->nilai !== null)
                        <div class="text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nilai</p>
                            <p class="text-4xl font-black {{ $pengumpulan->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5">
                                {{ $pengumpulan->nilai }}
                            </p>
                        </div>
                    @else
                        <div class="px-4 py-2 bg-amber-50 rounded-xl">
                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Menunggu Penilaian</p>
                        </div>
                    @endif
                </div>

                @php
                    $extension = strtolower(pathinfo($pengumpulan->file_jawaban, PATHINFO_EXTENSION));
                @endphp

                @if($pengumpulan->file_jawaban)
                    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        @if($extension === 'pdf')
                            <a href="{{ route('siswa.tugas.jawaban.view', $tugas->id) }}"
                               class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Tampilkan PDF Jawaban
                            </a>
                        @else
                            <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" target="_blank"
                               class="inline-flex items-center px-5 py-3 bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-100 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Unduh Jawaban
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @elseif($tugas->deadline >= now())
            {{-- Form Kumpul Tugas --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Upload Jawaban</p>
                <form action="{{ route('siswa.tugas.kumpul', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center hover:border-indigo-400 transition-colors">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-sm text-gray-500 mb-1">Pilih file jawaban</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-4">PDF, DOC, DOCX, ZIP — Maks 10MB</p>
                        <input type="file" name="file_jawaban" id="file_jawaban" required
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 text-white text-xs font-black uppercase tracking-widest py-4 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        Kumpulkan Tugas
                    </button>
                </form>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-rose-100 dark:border-rose-900/30 p-8 text-center">
                <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.2em]">Waktu Pengumpulan Habis</p>
                <p class="text-sm text-gray-500 mt-1">Deadline tugas ini sudah berakhir dan Anda belum mengumpulkan jawaban.</p>
            </div>
        @endif
    </div>
</x-app-layout>
