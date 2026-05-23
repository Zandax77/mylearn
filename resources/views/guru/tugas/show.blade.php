<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('guru.tugas.index') }}" class="flex items-center text-xs font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Daftar Tugas
                    </a>
                    <span class="text-gray-200 dark:text-gray-600">/</span>
                    <span class="text-xs font-bold text-gray-400">{{ $tugas->mapel->nama_mapel }}</span>
                </div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    {{ $tugas->judul_tugas }}
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">
                    Deadline: 
                    <span class="{{ $tugas->deadline < now() ? 'text-rose-500' : 'text-gray-500' }}">
                        {{ $tugas->deadline->format('d M Y, H:i') }}
                    </span>
                </p>
            </div>
            @if($tugas->file_tugas)
                <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank"
                   class="inline-flex items-center px-5 py-2.5 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-100 transition-all shrink-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Lihat Soal Tugas
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-8 max-w-6xl mx-auto">

        {{-- FLOATING ALERT --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="fixed top-24 right-8 z-[100]" x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-x-8">
                <div class="bg-white dark:bg-gray-800 border-l-4 border-emerald-500 shadow-2xl rounded-2xl p-5 flex items-center min-w-[320px]">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0 mr-4">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Berhasil</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-auto p-1 text-gray-300 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- STAT CARDS --}}
        @php
            $totalKumpul   = $tugas->pengumpulan->count();
            $sudahDinilai  = $tugas->pengumpulan->whereNotNull('nilai')->count();
            $belumDinilai  = $totalKumpul - $sudahDinilai;
            $rataRata      = $totalKumpul > 0 ? round($tugas->pengumpulan->whereNotNull('nilai')->avg('nilai'), 1) : null;
            $tertinggi     = $totalKumpul > 0 ? $tugas->pengumpulan->whereNotNull('nilai')->max('nilai') : null;
            $terendah      = $totalKumpul > 0 ? $tugas->pengumpulan->whereNotNull('nilai')->min('nilai') : null;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Pengumpulan</p>
                <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $totalKumpul }}</p>
                <p class="text-[10px] text-gray-400 font-bold mt-1">siswa mengumpulkan</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Belum Dinilai</p>
                <p class="text-4xl font-black {{ $belumDinilai > 0 ? 'text-amber-500' : 'text-emerald-500' }}">{{ $belumDinilai }}</p>
                <p class="text-[10px] text-gray-400 font-bold mt-1">menunggu penilaian</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Rata-rata Nilai</p>
                <p class="text-4xl font-black {{ $rataRata >= 75 ? 'text-emerald-500' : ($rataRata !== null ? 'text-rose-500' : 'text-gray-300') }}">
                    {{ $rataRata ?? '—' }}
                </p>
                <p class="text-[10px] text-gray-400 font-bold mt-1">dari 100</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Nilai Tertinggi</p>
                <p class="text-4xl font-black text-indigo-500">{{ $tertinggi ?? '—' }}</p>
                <p class="text-[10px] text-gray-400 font-bold mt-1">terendah: {{ $terendah ?? '—' }}</p>
            </div>
        </div>

        {{-- TABEL PENGUMPULAN --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-tight text-sm">Daftar Pengumpulan</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $sudahDinilai }} / {{ $totalKumpul }} sudah dinilai</p>
                </div>
                @if($totalKumpul > 0)
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-32 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all"
                                 style="width: {{ $totalKumpul > 0 ? round(($sudahDinilai / $totalKumpul) * 100) : 0 }}%"></div>
                        </div>
                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            {{ $totalKumpul > 0 ? round(($sudahDinilai / $totalKumpul) * 100) : 0 }}%
                        </span>
                    </div>
                @endif
            </div>

            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($tugas->pengumpulan as $p)
                    <div class="px-8 py-6 flex flex-col md:flex-row md:items-center gap-5">
                        {{-- Avatar & Info Siswa --}}
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="w-11 h-11 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-black text-sm shrink-0">
                                {{ substr($p->siswa->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ $p->siswa->name }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                    Dikumpulkan {{ $p->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Tombol Download --}}
                        <a href="{{ Storage::url($p->file_jawaban) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-100 transition-all shrink-0">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Jawaban
                        </a>

                        {{-- Badge Status Nilai --}}
                        @if($p->nilai !== null)
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="text-center">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-xl
                                        {{ $p->nilai >= 85 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30' :
                                           ($p->nilai >= 70 ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30' :
                                           'bg-rose-50 text-rose-600 dark:bg-rose-900/30') }}">
                                        {{ $p->nilai }}
                                    </div>
                                    <p class="text-[9px] font-black uppercase tracking-widest mt-1
                                        {{ $p->nilai >= 85 ? 'text-emerald-500' : ($p->nilai >= 70 ? 'text-amber-500' : 'text-rose-500') }}">
                                        {{ $p->nilai >= 85 ? 'Baik' : ($p->nilai >= 70 ? 'Cukup' : 'Kurang') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Form Beri / Edit Nilai --}}
                        <form action="{{ route('guru.tugas.nilai', [$tugas->id, $p->id]) }}" method="POST"
                              class="flex items-center gap-2 shrink-0" x-data="{ editing: {{ $p->nilai === null ? 'true' : 'false' }} }">
                            @csrf
                            <div x-show="editing" class="flex items-center gap-2">
                                <input type="number" name="nilai" min="0" max="100"
                                       value="{{ $p->nilai }}"
                                       placeholder="0–100"
                                       class="w-24 px-3 py-2.5 text-sm font-bold text-center bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                       required>
                                <button type="submit"
                                        class="px-5 py-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 dark:shadow-none">
                                    {{ $p->nilai !== null ? 'Update' : 'Simpan' }}
                                </button>
                                @if($p->nilai !== null)
                                    <button type="button" @click="editing = false"
                                            class="px-3 py-2.5 bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                                        Batal
                                    </button>
                                @endif
                            </div>
                            @if($p->nilai !== null)
                                <button type="button" x-show="!editing" @click="editing = true"
                                        class="px-4 py-2.5 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-all border border-gray-100 dark:border-gray-600">
                                    Edit Nilai
                                </button>
                            @endif
                        </form>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada siswa yang mengumpulkan</p>
                        <p class="text-sm text-gray-400 mt-1">Siswa akan muncul di sini setelah mengumpulkan jawaban.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
