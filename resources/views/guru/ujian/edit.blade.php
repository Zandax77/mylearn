<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Ujian</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form action="{{ route('guru.ujians.update', $ujian->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="judul" value="Judul (Misal: Bank Soal Kuis Bab 1)" />
                        <x-text-input id="judul" name="judul" type="text" class="block w-full mt-1" value="{{ old('judul', $ujian->judul) }}" required />
                    </div>
                    <div>
                        <x-input-label for="mapel_id" value="Mata Pelajaran" />
                        <select name="mapel_id" class="block w-full mt-1 rounded border-gray-300 bg-white text-gray-900 dark:bg-gray-900 dark:text-white" required>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ $ujian->mapel_id == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="bab_id" value="Bab / Topik" />
                        <select name="bab_id" class="block w-full mt-1 rounded border-gray-300 bg-white text-gray-900 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Bab --</option>
                            @foreach($mapels as $mapel)
                                @foreach($mapel->babs as $bab)
                                    <option value="{{ $bab->id }}" {{ $ujian->bab_id == $bab->id ? 'selected' : '' }}>Bab {{ $bab->urutan }}: {{ $bab->judul }} ({{ $mapel->nama_mapel }})</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tipe" value="Tipe Ujian" />
                        <select name="tipe" class="block w-full mt-1 rounded border-gray-300 bg-white text-gray-900 dark:bg-gray-900 dark:text-white" required>
                            <option value="kuis" {{ $ujian->tipe == 'kuis' ? 'selected' : '' }}>Kuis</option>
                            <option value="uts" {{ $ujian->tipe == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ $ujian->tipe == 'uas' ? 'selected' : '' }}>UAS</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="jumlah_soal_tampil" value="Jumlah Soal yang Ditampilkan (Acak)" />
                            <x-text-input id="jumlah_soal_tampil" name="jumlah_soal_tampil" type="number" min="1" class="block w-full mt-1" value="{{ old('jumlah_soal_tampil', $ujian->jumlah_soal_tampil) }}" required />
                            <p class="text-xs text-gray-500 mt-1">Siswa akan mendapatkan soal acak sejumlah ini. Durasi otomatis: 1 mnt/soal.</p>
                        </div>
                        <div>
                            <x-input-label for="passing_grade" value="Syarat Lulus (Passing Grade %)" />
                            <x-text-input id="passing_grade" name="passing_grade" type="number" min="0" max="100" class="block w-full mt-1" value="{{ old('passing_grade', $ujian->passing_grade) }}" required />
                            <p class="text-xs text-gray-500 mt-1">Minimal persentase kelulusan (Default: 80%).</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="mulai_pada" value="Waktu Mulai Ujian (Opsional)" />
                            <x-text-input id="mulai_pada" name="mulai_pada" type="datetime-local" class="block w-full mt-1" value="{{ old('mulai_pada', $ujian->mulai_pada ? $ujian->mulai_pada->format('Y-m-d\TH:i') : '') }}" />
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika ingin ujian dapat diakses langsung.</p>
                        </div>
                        <div>
                            <x-input-label for="selesai_pada" value="Waktu Selesai Ujian (Opsional)" />
                            <x-text-input id="selesai_pada" name="selesai_pada" type="datetime-local" class="block w-full mt-1" value="{{ old('selesai_pada', $ujian->selesai_pada ? $ujian->selesai_pada->format('Y-m-d\TH:i') : '') }}" />
                            <p class="text-xs text-gray-500 mt-1">Batas akhir pengerjaan siswa.</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
