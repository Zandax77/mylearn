<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Tugas;
use App\Models\ProgresMateri;
use App\Models\HasilUjian;
use App\Models\PengumpulanTugas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            $totalSiswa = User::where('role', 'siswa')->count();
            $totalGuru = User::where('role', 'guru')->count();
            $totalKelas = Kelas::count();

            // Logic for Teacher Progress Chart
            $mapels = Mapel::with(['babs', 'ujians', 'materis'])->get();
            $readyMapels = 0;
            $inProgressMapels = 0;

            foreach ($mapels as $mapel) {
                $isReady = $mapel->babs->count() > 0 &&
                           $mapel->materis->count() > 0 &&
                           $mapel->ujians->where('tipe', 'kuis')->count() > 0;
                $isReady ? $readyMapels++ : $inProgressMapels++;
            }

            // Logic for Student Activity Chart
            $activeStudents = User::where('role', 'siswa')
                ->whereHas('progresMateris')
                ->whereHas('hasilUjians')
                ->count();
            $inactiveStudents = max(0, $totalSiswa - $activeStudents);

            // Logic for Student Activity per Class Chart
            $kelasList = Kelas::with(['siswa' => function ($q) {
                $q->whereHas('progresMateris')->whereHas('hasilUjians');
            }])->get();

            $kelasLabels = $kelasList->pluck('nama_kelas')->toArray();
            $kelasActiveCounts = $kelasList->map(function ($k) {
                return $k->siswa->count();
            })->toArray();

            // --- NEW: LMS Activity (last 7 days) ---
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $labels7d = [];
            for ($i = 0; $i < 7; $i++) {
                $labels7d[] = Carbon::now()->subDays(6 - $i)->format('d M');
            }

            $siswaActiveCountsByDay = array_fill(0, 7, 0);
            $guruActiveCountsByDay = array_fill(0, 7, 0);

            // Siswa: materi dibaca + ujian selesai
            $progresMateriExists = Schema::hasTable('progres_materis');
            $hasilUjianExists = Schema::hasTable('hasil_ujians');

            if ($progresMateriExists) {
                $rows = ProgresMateri::query()
                    ->selectRaw('DATE(read_at) as day, COUNT(*) as total')
                    ->where('is_read', true)
                    ->whereBetween('read_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $siswaActiveCountsByDay[$idx] += (int) $total;
                }
            }

            if ($hasilUjianExists) {
                $rows = HasilUjian::query()
                    ->selectRaw('DATE(completed_at) as day, COUNT(*) as total')
                    ->whereNotNull('completed_at')
                    ->whereBetween('completed_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $siswaActiveCountsByDay[$idx] += (int) $total;
                }
            }

            // Opsional: tugas dikumpulkan (timestamp: created_at)
            if (Schema::hasTable('pengumpulan_tugas')) {
                $rows = PengumpulanTugas::query()
                    ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $siswaActiveCountsByDay[$idx] += (int) $total;
                }
            }

            // Guru: hitung aksi dari konten terkait guru
            // - Materi (tanggal_upload) per hari
            // - Tugas (created_at) per hari
            // - Ujian (created_at) per hari
            // - Bab (created_at) per hari
            // Semua data dipetakan ke guru melalui mapel.guru_id

            $materiExists = Schema::hasTable('materis');
            $tugasExists = Schema::hasTable('tugas');
            $babExists = Schema::hasTable('babs');
            $ujiansExists = Schema::hasTable('ujians');

            if ($materiExists) {
                $rows = \App\Models\Materi::query()
                    ->join('mapels', 'materis.mapel_id', '=', 'mapels.id')
                    ->selectRaw('DATE(materis.tanggal_upload) as day, COUNT(*) as total')
                    ->whereBetween('materis.tanggal_upload', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
                }
            }

            if ($babExists) {
                $rows = \App\Models\Bab::query()
                    ->join('mapels', 'babs.mapel_id', '=', 'mapels.id')
                    ->selectRaw('DATE(babs.created_at) as day, COUNT(*) as total')
                    ->whereBetween('babs.created_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
                }
            }

            if ($tugasExists) {
                $rows = Tugas::query()
                    ->join('mapels', 'tugas.mapel_id', '=', 'mapels.id')
                    ->selectRaw('DATE(tugas.created_at) as day, COUNT(*) as total')
                    ->whereBetween('tugas.created_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
                }
            }

            if ($ujiansExists) {
                $rows = \App\Models\Ujian::query()
                    ->join('mapels', 'ujians.mapel_id', '=', 'mapels.id')
                    ->selectRaw('DATE(ujians.created_at) as day, COUNT(*) as total')
                    ->whereBetween('ujians.created_at', [$startDate, $endDate])
                    ->groupBy('day')
                    ->pluck('total', 'day');

                foreach ($rows as $day => $total) {
                    $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                    if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
                }
            }

            $pageData = compact(
                'totalSiswa', 'totalGuru', 'totalKelas',
                'readyMapels', 'inProgressMapels',
                'activeStudents', 'inactiveStudents',
                'kelasLabels', 'kelasActiveCounts',
                'labels7d', 'siswaActiveCountsByDay', 'guruActiveCountsByDay'
            );

            return view('dashboard.admin', $pageData);
        } elseif ($role === 'guru') {
            $tugasAktif = Tugas::whereHas('mapel', function ($q) {
                $q->where('guru_id', auth()->id());
            })->count();

            $mapels = Mapel::where('guru_id', auth()->id())->with(['kelas.siswa'])->get();

            return view('dashboard.guru', compact('tugasAktif', 'mapels'));
        } else {
            return view('dashboard.siswa');
        }
    }
}

