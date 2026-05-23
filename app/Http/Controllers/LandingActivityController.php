<?php

namespace App\Http\Controllers;

use App\Services\AdminBootstrapper;
use App\Models\Mapel;
use App\Models\Tugas;
use App\Models\User;
use App\Models\ProgresMateri;
use App\Models\HasilUjian;
use App\Models\PengumpulanTugas;
use App\Models\Kelas;
use App\Models\Bab;
use App\Models\Materi;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LandingActivityController extends Controller
{
    public function index()
    {
        // Pastikan super admin tersedia saat aplikasi baru diaktifkan / belum ada user
        AdminBootstrapper::ensureSuperAdmin();

        // last 7 days range
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $labels7d = [];
        for ($i = 0; $i < 7; $i++) {
            $labels7d[] = Carbon::now()->subDays(6 - $i)->format('d M');
        }

        $siswaActiveCountsByDay = array_fill(0, 7, 0);
        $guruActiveCountsByDay = array_fill(0, 7, 0);

        // Siswa: materi dibaca + ujian selesai (+ tugas dikumpulkan bila ada)
        if (Schema::hasTable('progres_materis')) {
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

        if (Schema::hasTable('hasil_ujians')) {
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

        // Guru (aggregated global): hitung aktivitas konten yang dikelola guru via mapel.guru_id.
        // Catatan: chart ini tidak spesifik guru login, tapi total semua guru.
        if (Schema::hasTable('materis')) {
            $rows = Materi::query()
                ->join('mapels', 'materis.mapel_id', '=', 'mapels.id')
                ->selectRaw('DATE(materis.tanggal_upload) as day, COUNT(*) as total')
                ->whereNotNull('mapels.guru_id')
                ->whereBetween('materis.tanggal_upload', [$startDate, $endDate])
                ->groupBy('day')
                ->pluck('total', 'day');

            foreach ($rows as $day => $total) {
                $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
            }
        }

        if (Schema::hasTable('babs')) {
            $rows = Bab::query()
                ->join('mapels', 'babs.mapel_id', '=', 'mapels.id')
                ->selectRaw('DATE(babs.created_at) as day, COUNT(*) as total')
                ->whereNotNull('mapels.guru_id')
                ->whereBetween('babs.created_at', [$startDate, $endDate])
                ->groupBy('day')
                ->pluck('total', 'day');

            foreach ($rows as $day => $total) {
                $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
            }
        }

        if (Schema::hasTable('tugas')) {
            $rows = Tugas::query()
                ->join('mapels', 'tugas.mapel_id', '=', 'mapels.id')
                ->selectRaw('DATE(tugas.created_at) as day, COUNT(*) as total')
                ->whereNotNull('mapels.guru_id')
                ->whereBetween('tugas.created_at', [$startDate, $endDate])
                ->groupBy('day')
                ->pluck('total', 'day');

            foreach ($rows as $day => $total) {
                $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
            }
        }

        if (Schema::hasTable('ujians')) {
            $rows = Ujian::query()
                ->join('mapels', 'ujians.mapel_id', '=', 'mapels.id')
                ->selectRaw('DATE(ujians.created_at) as day, COUNT(*) as total')
                ->whereNotNull('mapels.guru_id')
                ->whereBetween('ujians.created_at', [$startDate, $endDate])
                ->groupBy('day')
                ->pluck('total', 'day');

            foreach ($rows as $day => $total) {
                $idx = Carbon::parse($day)->diffInDays(Carbon::now()->startOfDay()->subDays(6));
                if ($idx >= 0 && $idx < 7) $guruActiveCountsByDay[$idx] += (int) $total;
            }
        }


        // Landing page base metrics (same as existing route / closure)
        $totalSiswa = Schema::hasTable('users') ? User::where('role', 'siswa')->count() : 0;
        $totalGuru = Schema::hasTable('users') ? User::where('role', 'guru')->count() : 0;
        $totalKelas = Schema::hasTable('kelas') ? Kelas::count() : 0;
        $totalMapel = Schema::hasTable('mapels') ? Mapel::count() : 0;

        return view('welcome', compact(
            'totalSiswa', 'totalGuru', 'totalKelas', 'totalMapel',
            'labels7d', 'siswaActiveCountsByDay', 'guruActiveCountsByDay'
        ));
    }
}

