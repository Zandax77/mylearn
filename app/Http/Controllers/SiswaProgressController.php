<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\User;
use App\Models\Bab;
use App\Models\HasilUjian;
use App\Models\ProgresMateri;
use Illuminate\Http\Request;

class SiswaProgressController extends Controller
{
    public function index($mapel_id)
    {
        $mapel = Mapel::with([
                'kelas.siswa.kelas',
                'kelas.siswa',
                'babs.materis',
                'babs.ujians',
            ])->findOrFail($mapel_id);

        if ($mapel->guru_id !== auth()->id()) abort(403);

        $students = $mapel->kelas
            ->flatMap(fn ($kelas) => $kelas->siswa)
            ->unique('id')
            ->values();

        $materiIds = $mapel->babs
            ->flatMap(fn ($bab) => $bab->materis)
            ->pluck('id')
            ->unique()
            ->values();

        $kuisIds = $mapel->babs
            ->flatMap(fn ($bab) => $bab->ujians)
            ->where('tipe', 'kuis')
            ->pluck('id')
            ->unique()
            ->values();

        $totalItems = $mapel->babs->sum(function ($bab) {
            return $bab->materis->count() + $bab->ujians->where('tipe', 'kuis')->count();
        });

        $studentIds = $students->pluck('id')->values();

        // Materi completed per siswa (sekali query, hindari N+1)
        $completedMaterisByStudent = $materiIds->isEmpty() || $studentIds->isEmpty()
            ? collect()
            : ProgresMateri::query()
                ->selectRaw('siswa_id, COUNT(DISTINCT materi_id) as completed')
                ->whereIn('siswa_id', $studentIds)
                ->whereIn('materi_id', $materiIds)
                ->groupBy('siswa_id')
                ->pluck('completed', 'siswa_id');

        // Quis lulus per siswa (sekali query, hindari N+1)
        $passedQuizzesByStudent = $kuisIds->isEmpty() || $studentIds->isEmpty()
            ? collect()
            : HasilUjian::query()
                ->selectRaw('siswa_id, COUNT(DISTINCT ujian_id) as passed')
                ->whereIn('siswa_id', $studentIds)
                ->whereIn('ujian_id', $kuisIds)
                ->where('is_lulus', true)
                ->groupBy('siswa_id')
                ->pluck('passed', 'siswa_id');

        foreach ($students as $student) {
            $completedMateris = (int) ($completedMaterisByStudent[$student->id] ?? 0);
            $passedQuizzes = (int) ($passedQuizzesByStudent[$student->id] ?? 0);

            $student->completed_count = $completedMateris + $passedQuizzes;
            $progress = $totalItems > 0
                ? ($student->completed_count / $totalItems) * 100
                : 0;

            $student->progress_percent = max(0, min(100, (float) $progress));
        }

        $students = $students->sortByDesc('progress_percent')->values();

        return view('guru.progress.index', compact('mapel', 'students'));
    }

    public function show($mapel_id, $siswa_id)
    {
        $mapel = Mapel::with(['babs.materis', 'babs.ujians'])->findOrFail($mapel_id);
        $student = User::findOrFail($siswa_id);

        if ($mapel->guru_id !== auth()->id()) abort(403);

        $progress = $mapel->babs->map(function($bab) use ($siswa_id) {
            $bab->materis->each(function($materi) use ($siswa_id) {
                $materi->is_read = ProgresMateri::where('siswa_id', $siswa_id)
                    ->where('materi_id', $materi->id)
                    ->exists();
            });

            $kuis = $bab->ujians->where('tipe', 'kuis')->first();
            if ($kuis) {
                $kuis->hasil = HasilUjian::where('siswa_id', $siswa_id)
                    ->where('ujian_id', $kuis->id)
                    ->orderByDesc('nilai')
                    ->first();
            }

            return $bab;
        });

        return view('guru.progress.show', compact('mapel', 'student', 'progress'));
    }
}
