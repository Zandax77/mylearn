<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Mengerjakan: {{ $ujian->judul }}
            </h2>
            <div id="timer" class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-bold border border-red-200">
                Waktu: <span id="time-display">--:--</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form id="ujian-form" action="{{ route('siswa.ujian.submit', $ujian->id) }}" method="POST">
                        @csrf
                        
                        @foreach($soals as $index => $soal)
                            <div class="mb-8 {{ !$loop->last ? 'pb-8 border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                    {{ $index + 1 }}. {{ $soal->pertanyaan }}
                                </h3>
                                
                                @if($ujian->tipe === 'kuis' || !isset($soal->tipe_soal) || $soal->tipe_soal === 'pg')
                                    {{-- Old Soal Style or BankSoal PG Style --}}
                                    <div class="space-y-3">
                                        @if(isset($soal->tipe_soal) && $soal->tipe_soal === 'pg')
                                            @foreach($soal->opsi as $key => $opsi)
                                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi }}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" required>
                                                    <span class="ml-3 text-gray-700 dark:text-gray-300">{{ $opsi }}</span>
                                                </label>
                                            @endforeach
                                        @else
                                            @foreach(['a', 'b', 'c', 'd'] as $key)
                                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $key }}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" required>
                                                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                                                        <span class="font-bold mr-1 uppercase">{{ $key }}.</span> {{ $soal->{'opsi_' . $key} }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                @elseif($soal->tipe_soal === 'bs')
                                    <div class="space-y-3">
                                        @foreach(['Benar', 'Salah'] as $opsi)
                                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                                <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi }}" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" required>
                                                <span class="ml-3 text-gray-700 dark:text-gray-300 font-bold">{{ $opsi }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($soal->tipe_soal === 'jodoh')
                                    <div class="space-y-4">
                                        <p class="text-sm text-gray-500 mb-2">Pilih pasangan yang tepat untuk setiap item di bawah ini:</p>
                                        @php
                                            // The options are key-value pairs representing the correct matches in DB
                                            // But for the view, we need to shuffle the values to make it a dropdown
                                            $keys = array_keys($soal->opsi);
                                            $values = array_values($soal->opsi);
                                            shuffle($values);
                                        @endphp
                                        @foreach($keys as $key)
                                            <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-100 dark:border-gray-600">
                                                <div class="w-1/2 font-semibold text-gray-800 dark:text-gray-200">{{ $key }}</div>
                                                <div class="w-1/2">
                                                    <select name="jawaban[{{ $soal->id }}][{{ $key }}]" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white" required>
                                                        <option value="">-- Pilih Pasangan --</option>
                                                        @foreach($values as $val)
                                                            <option value="{{ $val }}">{{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="mt-8 flex justify-center">
                            <x-primary-button class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-lg" onclick="return confirm('Apakah Anda yakin ingin mengumpulkan ujian ini?')">
                                Kumpulkan Jawaban
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let totalSeconds = {{ $durasi * 60 }};
            const timeDisplay = document.getElementById('time-display');
            const form = document.getElementById('ujian-form');

            const timer = setInterval(function() {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    alert('Waktu habis! Jawaban Anda akan otomatis dikumpulkan.');
                    form.submit();
                }

                totalSeconds--;
            }, 1000);
        });
    </script>
</x-app-layout>
