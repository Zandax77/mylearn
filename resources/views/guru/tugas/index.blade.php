<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    Kelola Tugas
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Buat, pantau, dan nilai tugas siswa</p>
            </div>
            <a href="{{ route('guru.tugas.create') }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 dark:shadow-none shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Tugas Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-5xl mx-auto">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
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

        @forelse($tugas as $t)
            @php
                $totalKumpul  = $t->pengumpulan->count();
                $belumDinilai = $t->pengumpulan->whereNull('nilai')->count();
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border {{ $belumDinilai > 0 ? 'border-amber-200 dark:border-amber-800' : 'border-gray-100 dark:border-gray-700' }} overflow-hidden">
                <div class="p-6">
                    {{-- Baris 1: Badge + Judul --}}
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-indigo-50 text-indigo-600">
                            {{ $t->mapel->nama_mapel }}
                        </span>
                        @if($belumDinilai > 0)
                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-amber-50 text-amber-600">
                                ⚠ {{ $belumDinilai }} Belum Dinilai
                            </span>
                        @elseif($totalKumpul > 0)
                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-emerald-50 text-emerald-600">
                                ✓ Semua Sudah Dinilai
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">{{ $t->judul_tugas }}</h3>

                    {{-- Baris 2: Meta info --}}
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-1">
                        <span class="text-xs font-bold text-gray-500">
                            Deadline:
                            <span class="font-black {{ $t->deadline < now() ? 'text-rose-500' : 'text-gray-700 dark:text-gray-300' }}">
                                {{ $t->deadline->format('d M Y, H:i') }}
                            </span>
                        </span>
                        <span class="text-xs font-bold text-gray-500">
                            <span class="font-black text-gray-700 dark:text-gray-300">{{ $totalKumpul }}</span> pengumpulan masuk
                        </span>
                    </div>
                </div>

                {{-- Footer Kartu --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between gap-3">
                    @if($t->file_tugas)
                        <a href="{{ Storage::url($t->file_tugas) }}" target="_blank"
                           class="inline-flex items-center text-xs font-bold text-indigo-500 hover:text-indigo-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Soal
                        </a>
                    @else
                        <span></span>
                    @endif

                    <a href="{{ route('guru.tugas.show', $t->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        {{ $belumDinilai > 0 ? 'Nilai Sekarang' : 'Lihat Pengumpulan' }}
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-5.586a1 1 0 01-.707-.293l-5.414-5.414A1 1 0 013 10.586V19a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada tugas dibuat</p>
                <p class="text-sm text-gray-400 mt-1 mb-6">Mulai dengan membuat tugas pertama untuk siswa Anda.</p>
                <a href="{{ route('guru.tugas.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tugas Pertama
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
