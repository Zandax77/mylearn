<?php

namespace Database\Seeders;

use App\Models\Bab;
use App\Models\HasilUjian;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Materi;
use App\Models\PengumpulanTugas;
use App\Models\ProgresMateri;
use App\Models\Soal;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LmsSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign keys jika DB tidak sesuai.
        try {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } catch (\Throwable $e) {
            // ignore
        }

        // Truncate tabel yang terlibat
        User::truncate();
        Kelas::truncate();
        Mapel::truncate();
        Bab::truncate();
        Materi::truncate();
        Tugas::truncate();
        Ujian::truncate();
        Soal::truncate();

        DB::table('kelas_mapel')->truncate();
        if (DB::getSchemaBuilder()->hasTable('progres_materis')) {
            DB::table('progres_materis')->truncate();
        }
        if (DB::getSchemaBuilder()->hasTable('hasil_ujians')) {
            DB::table('hasil_ujians')->truncate();
        }
        if (DB::getSchemaBuilder()->hasTable('pengumpulan_tugas')) {
            DB::table('pengumpulan_tugas')->truncate();
        }

        try {
            DB::statement('PRAGMA foreign_keys = ON;');
        } catch (\Throwable $e) {
            // ignore
        }

        $today = Carbon::now();
        $daysBack = 6; // 7 hari terakhir

        // =============================
        // USERS
        // =============================
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@lms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $guru1 = User::create([
            'name' => 'Budi Guru 1',
            'email' => 'guru1@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);
        $guru2 = User::create([
            'name' => 'Siti Guru 2',
            'email' => 'guru2@lms.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // =============================
        // KELAS + SISWA (2 kelas)
        // =============================
        $kelasA = Kelas::create(['nama_kelas' => '10-A IPA', 'wali_kelas' => null]);
        $kelasB = Kelas::create(['nama_kelas' => '10-B IPA', 'wali_kelas' => null]);

        $siswaPerKelas = 6;
        $siswa = collect();

        for ($i = 1; $i <= $siswaPerKelas; $i++) {
            $siswa->push(User::create([
                'name' => "Andi Siswa A{$i}",
                'email' => "siswaA{$i}@lms.com",
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas_id' => $kelasA->id,
            ]));
        }
        for ($i = 1; $i <= $siswaPerKelas; $i++) {
            $siswa->push(User::create([
                'name' => "Andi Siswa B{$i}",
                'email' => "siswaB{$i}@lms.com",
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas_id' => $kelasB->id,
            ]));
        }

        // =============================
        // MAPEL (4 mapel total: 2 guru x 2)
        // =============================
        $mapelList = collect([
            Mapel::create(['nama_mapel' => 'Matematika Dasar (G1-M1)', 'guru_id' => $guru1->id]),
            Mapel::create(['nama_mapel' => 'Matematika Dasar (G1-M2)', 'guru_id' => $guru1->id]),
            Mapel::create(['nama_mapel' => 'Logika & Aljabar (G2-M1)', 'guru_id' => $guru2->id]),
            Mapel::create(['nama_mapel' => 'Kalkulus Dasar (G2-M2)', 'guru_id' => $guru2->id]),
        ]);

        foreach ($mapelList as $m) {
            foreach ([$kelasA, $kelasB] as $k) {
                DB::table('kelas_mapel')->insert([
                    'kelas_id' => $k->id,
                    'mapel_id' => $m->id,
                    'created_at' => $today,
                    'updated_at' => $today,
                ]);
            }
        }

        // Bank soal kuis
        $kuisQuestionBank = [
            ['q' => 'Berapakah 5 + 5?', 'a' => '10', 'b' => '15', 'c' => '20', 'd' => '5', 'ans' => 'a'],
            ['q' => 'Berapakah 10 - 3?', 'a' => '5', 'b' => '7', 'c' => '8', 'd' => '6', 'ans' => 'b'],
            ['q' => 'Jika x + 2 = 5, berapakah x?', 'a' => '1', 'b' => '2', 'c' => '3', 'd' => '4', 'ans' => 'c'],
            ['q' => 'Berapakah hasil dari 2 * 4?', 'a' => '6', 'b' => '8', 'c' => '10', 'd' => '12', 'ans' => 'b'],
            ['q' => 'Berapakah hasil dari 9 / 3?', 'a' => '3', 'b' => '2', 'c' => '4', 'd' => '1', 'ans' => 'a'],
            ['q' => 'Manakah yang merupakan bilangan genap?', 'a' => '1', 'b' => '3', 'c' => '5', 'd' => '8', 'ans' => 'd'],
        ];

        // Untuk chart
        $materiPoolByMapel = collect();
        $kuisPoolByMapel = collect();

        foreach ($mapelList as $mIndex => $mapel) {
            for ($b = 1; $b <= 3; $b++) {
                $createdBabAt = $today->copy()->subDays(($mIndex + $b) % $daysBack);

                $bab = Bab::create([
                    'mapel_id' => $mapel->id,
                    'judul' => "Bab {$b}: Konten untuk {$mapel->nama_mapel}",
                    'urutan' => $b,
                    'created_at' => $createdBabAt,
                    'updated_at' => $createdBabAt,
                ]);

                // 2 materi per bab
                for ($mat = 1; $mat <= 2; $mat++) {
                    $offset = ($mIndex + $b + $mat) % ($daysBack + 1);
                    $tanggalUpload = $today->copy()->subDays($offset)->toDateString();
                    $createdAt = $today->copy()->subDays($offset);

                    $materi = Materi::create([
                        'judul' => "Materi {$mat} ({$bab->judul})",
                        'file' => 'materi/sample.pdf',
                        'mapel_id' => $mapel->id,
                        'tanggal_upload' => $tanggalUpload,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);

                    $materiPoolByMapel->put(
                        $mapel->id,
                        ($materiPoolByMapel->get($mapel->id) ?? collect())->push($materi)
                    );
                }

                // 1 kuis per bab
                $kuisCreatedAt = $today->copy()->subDays(($mIndex + $b) % ($daysBack + 1));
                $kuis = Ujian::create([
                    'judul' => "Kuis Bab {$b} - {$mapel->nama_mapel}",
                    'tipe' => 'kuis',
                    'mapel_id' => $mapel->id,
                    'bab_id' => $bab->id,
                    'passing_grade' => 80,
                    'durasi_menit' => 5,
                    'created_at' => $kuisCreatedAt,
                    'updated_at' => $kuisCreatedAt,
                ]);

                $kuisPoolByMapel->put(
                    $mapel->id,
                    ($kuisPoolByMapel->get($mapel->id) ?? collect())->push($kuis)
                );

                foreach ($kuisQuestionBank as $qb) {
                    Soal::create([
                        'ujian_id' => $kuis->id,
                        'pertanyaan' => $qb['q'],
                        'opsi_a' => $qb['a'],
                        'opsi_b' => $qb['b'],
                        'opsi_c' => $qb['c'],
                        'opsi_d' => $qb['d'],
                        'jawaban_benar' => $qb['ans'],
                        'created_at' => $today,
                        'updated_at' => $today,
                    ]);
                }

                // 1 tugas per bab
                $tugasCreatedAt = $today->copy()->subDays(($mIndex + $b + 2) % ($daysBack + 1));
                $tugas = Tugas::create([
                    'judul_tugas' => "Tugas Bab {$b} - {$mapel->nama_mapel}",
                    'deadline' => $today->copy()->addDays(7)->toDateTimeString(),
                    'file_tugas' => null,
                    'mapel_id' => $mapel->id,
                    'created_at' => $tugasCreatedAt,
                    'updated_at' => $tugasCreatedAt,
                ]);

                // Pengumpulan tugas untuk beberapa siswa (untuk grafik 7 hari)
                foreach ($siswa as $siIndex => $stu) {
                    if ((($siIndex + $b + $mIndex) % 3) === 0) {
                        $offset = ($mIndex + $b + $siIndex) % ($daysBack + 1);
                        $createdAt = $today->copy()->subDays($offset);

                        PengumpulanTugas::create([
                            'tugas_id' => $tugas->id,
                            'siswa_id' => $stu->id,
                            'file_jawaban' => 'jawaban/sample.pdf',
                            'nilai' => 70 + (($siIndex + $b) % 30),
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt,
                        ]);
                    }
                }
            }

            // UTS per mapel
            $utsAt = $today->copy()->subDays(3);
            Ujian::create([
                'judul' => "Ujian Tengah Semester (UTS) - {$mapel->nama_mapel}",
                'tipe' => 'uts',
                'mapel_id' => $mapel->id,
                'bab_id' => null,
                'passing_grade' => 80,
                'durasi_menit' => 10,
                'created_at' => $utsAt,
                'updated_at' => $utsAt,
            ]);
        }

        // Aktivitas siswa
        foreach ($siswa as $idx => $stu) {
            foreach ($mapelList as $mIdx => $mapel) {
                $active = ((($idx + $mIdx) % 2) === 0);

                $materiPool = $materiPoolByMapel->get($mapel->id, collect());
                $kuisPool = $kuisPoolByMapel->get($mapel->id, collect());

                // Progres materi
                if ($active || ((($idx + $mIdx) % 3) === 0)) {
                    $materi = $materiPool->isNotEmpty() ? $materiPool->random() : null;
                    if ($materi) {
                        $offset = ($idx + $mIdx) % ($daysBack + 1);
                        $readAt = $today->copy()->subDays($offset);

                        ProgresMateri::create([
                            'siswa_id' => $stu->id,
                            'materi_id' => $materi->id,
                            'is_read' => true,
                            'read_at' => $readAt,
                            'created_at' => $readAt,
                            'updated_at' => $readAt,
                        ]);
                    }
                }

                // Hasil kuis
                if ($active || ((($idx + $mIdx) % 3) === 1)) {
                    $kuisPick = $kuisPool->isNotEmpty() ? $kuisPool->random() : null;
                    if ($kuisPick) {
                        $offset = ($idx + $mIdx + 1) % ($daysBack + 1);
                        $completedAt = $today->copy()->subDays($offset);
                        $isLulus = (((int) (($idx + $mIdx) % 5)) !== 0);

                        HasilUjian::create([
                            'siswa_id' => $stu->id,
                            'ujian_id' => $kuisPick->id,
                            'nilai' => 60 + (($idx + $mIdx) % 41),
                            'is_lulus' => $isLulus,
                            'completed_at' => $completedAt,
                            'created_at' => $completedAt,
                            'updated_at' => $completedAt,
                        ]);
                    }
                }
            }
        }

        echo "Dummy data created successfully!\n";
        echo "Admin: admin@lms.com / password\n";
        echo "Guru1: guru1@lms.com / password\n";
        echo "Guru2: guru2@lms.com / password\n";
        echo "Siswa: siswaA1@lms.com / password\n";
    }
}

