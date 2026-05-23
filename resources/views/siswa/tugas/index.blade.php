<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                Tugas &amp; Latihan
            </h2>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kerjakan dan kumpulkan sebelum deadline</p>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl mx-auto">
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

        @forelse($tugas as $t)
            @php $pengumpulan = $t->pengumpulan->first(); @endphp
            <a href="{{ route('siswa.tugas.show', $t->id) }}" class="block group">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border {{ $t->deadline < now() ? 'border-rose-100 dark:border-rose-900/30' : 'border-gray-100 dark:border-gray-700' }} p-6 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between gap-4">
                        {{-- Info Tugas --}}
                        <div class="flex-1 min-w-0">
                            <span class="inline-block px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg bg-amber-50 text-amber-600 mb-3">
                                {{ $t->mapel->nama_mapel }}
                            </span>
                            <h3 class="text-base font-black text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors truncate">
                                {{ $t->judul_tugas }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1 font-bold">
                                Deadline:
                                <span class="{{ $t->deadline < now() ? 'text-rose-500' : 'text-gray-600 dark:text-gray-300' }}">
                                    {{ $t->deadline->format('d M Y, H:i') }}
                                </span>
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="shrink-0 flex flex-col items-end gap-2">
                            @if($pengumpulan)
                                <span class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-emerald-50 text-emerald-600">
                                    ✓ Sudah Dikumpulkan
                                </span>
                                @if($pengumpulan->nilai !== null)
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nilai</p>
                                        <p class="text-2xl font-black {{ $pengumpulan->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $pengumpulan->nilai }}</p>
                                    </div>
                                @else
                                    <span class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-amber-50 text-amber-600">
                                        Menunggu Nilai
                                    </span>
                                @endif
                            @elseif($t->deadline < now())
                                <span class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-rose-50 text-rose-600">
                                    Terlambat
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-xl bg-indigo-50 text-indigo-600">
                                    Belum Dikumpulkan
                                </span>
                            @endif

                            <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-400 transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>

                    @if($t->file_tugas)
                        <div class="mt-4 pt-4 border-t border-gray-50 dark:border-gray-700">
                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">📎 Ada Lampiran Tugas</span>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
                <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Belum ada tugas tersedia</p>
                <p class="text-sm text-gray-400 mt-1">Tugas dari guru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
