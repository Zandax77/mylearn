<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('guru.tugas.index') }}" class="flex items-center text-xs font-bold text-gray-400 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <span class="text-gray-200 dark:text-gray-600">/</span>
            <div>
                <h2 class="font-black text-3xl text-gray-900 dark:text-white tracking-tighter uppercase leading-none">
                    Buat Tugas Baru
                </h2>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-0.5">Isi detail tugas untuk siswa</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('guru.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Judul Tugas --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-5">Informasi Tugas</p>
                <div class="space-y-5">
                    <div>
                        <label for="judul_tugas" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">
                            Judul Tugas <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="judul_tugas" name="judul_tugas"
                               value="{{ old('judul_tugas') }}"
                               class="block w-full px-4 py-3 text-sm font-medium bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder:text-gray-400"
                               placeholder="Contoh: Tugas Analisis Aljabar Linear"
                               required>
                        @error('judul_tugas')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">
                            Deskripsi Tugas <span class="text-gray-400 font-bold normal-case tracking-normal">(Opsional)</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="5"
                                  class="block w-full px-4 py-3 text-sm font-medium bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-gray-900 dark:text-white placeholder:text-gray-400 resize-none"
                                  placeholder="Jelaskan instruksi pengerjaan tugas, materi yang diujikan, format pengumpulan, atau petunjuk lainnya...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mapel_id" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">
                            Mata Pelajaran <span class="text-rose-500">*</span>
                        </label>
                        <select id="mapel_id" name="mapel_id" required
                                class="block w-full px-4 py-3 text-sm font-medium bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-gray-900 dark:text-white">
                            <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Deadline & Lampiran --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-5">Pengaturan & Lampiran</p>
                <div class="space-y-5">
                    <div>
                        <label for="deadline" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">
                            Batas Waktu (Deadline) <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" id="deadline" name="deadline"
                               value="{{ old('deadline') }}"
                               class="block w-full px-4 py-3 text-sm font-medium bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-gray-900 dark:text-white"
                               required>
                        @error('deadline')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="file_tugas" class="block text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-widest mb-2">
                            File Soal / Lampiran <span class="text-gray-400 font-bold normal-case tracking-normal">(Opsional)</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-center hover:border-indigo-400 transition-colors">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">PDF, DOC, DOCX, ZIP — Maks 10MB</p>
                            <input type="file" id="file_tugas" name="file_tugas"
                                   accept=".pdf,.doc,.docx,.zip"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                        </div>
                        @error('file_tugas')
                            <p class="mt-1.5 text-xs font-bold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('guru.tugas.index') }}"
                   class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tugas
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
