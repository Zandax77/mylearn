<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buat Bank Soal / Ujian</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <form action="{{ route('guru.ujians.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="judul" value="Judul (Misal: Bank Soal Kuis Bab 1)" />
                        <x-text-input id="judul" name="judul" type="text" class="block w-full mt-1" required />
                    </div>
                    <div>
                        <x-input-label for="mapel_id" value="Mata Pelajaran" />
                        <select name="mapel_id" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ $selectedMapel == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="bab_id" value="Bab / Topik" />
                        <select name="bab_id" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            <option value="">-- Pilih Bab --</option>
                            @foreach($mapels as $mapel)
                                @foreach($mapel->babs as $bab)
                                    <option value="{{ $bab->id }}" {{ $selectedBab == $bab->id ? 'selected' : '' }}>Bab {{ $bab->urutan }}: {{ $bab->judul }} ({{ $mapel->nama_mapel }})</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="tipe" value="Tipe Ujian" />
                        <select name="tipe" class="block w-full mt-1 rounded border-gray-300 dark:bg-gray-900 dark:text-white" required>
                            <option value="kuis">Kuis</option>
                            <option value="uts">UTS</option>
                            <option value="uas">UAS</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="jumlah_soal_tampil" value="Jumlah Soal yang Ditampilkan (Acak)" />
                            <x-text-input id="jumlah_soal_tampil" name="jumlah_soal_tampil" type="number" min="1" value="3" class="block w-full mt-1" required />
                            <p class="text-xs text-gray-500 mt-1">Siswa akan mendapatkan soal acak sejumlah ini. Durasi otomatis: 1 mnt/soal.</p>
                        </div>
                        <div>
                            <x-input-label for="passing_grade" value="Syarat Lulus (Passing Grade %)" />
                            <x-text-input id="passing_grade" name="passing_grade" type="number" min="0" max="100" value="80" class="block w-full mt-1" required />
                            <p class="text-xs text-gray-500 mt-1">Minimal persentase kelulusan (Default: 80%).</p>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-primary-button>Simpan Pengaturan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
