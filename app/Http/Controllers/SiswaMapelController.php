<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Bab;
use App\Models\HasilUjian;
use Illuminate\Http\Request;

class SiswaMapelController extends Controller
{
    public function index()
    {
        $kelasId = auth()->user()->kelas_id;
        
        if (!$kelasId) {
            return view('siswa.mapel.index', ['mapels' => []])->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        $mapels = Mapel::whereHas('kelas', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        })->with('guru')->get();

        return view('siswa.mapel.index', compact('mapels'));
    }

    public function show($mapel_id)
    {
        $mapel = Mapel::with(['babs' => function($q) {
            $q->orderBy('urutan');
        }, 'babs.materis', 'babs.ujians'])->findOrFail($mapel_id);

        $siswaId = auth()->id();
        
        // Progress tracking logic
        $babs = $mapel->babs->map(function($bab, $index) use ($siswaId, $mapel) {
            $isLocked = false;
            
            // Check if student has read all materials in THIS bab
            $totalMateriInBab = $bab->materis->count();
            $readMateriCount = \App\Models\ProgresMateri::where('siswa_id', $siswaId)
                ->whereIn('materi_id', $bab->materis->pluck('id'))
                ->count();
            
            $bab->all_materials_read = ($totalMateriInBab > 0 && $readMateriCount >= $totalMateriInBab);

            // Check if student has passed THIS bab's quiz
            $bab->has_passed_kuis = HasilUjian::where('siswa_id', $siswaId)
                ->whereHas('ujian', function($q) use ($bab) {
                    $q->where('bab_id', $bab->id)->where('tipe', 'kuis');
                })
                ->where('is_lulus', true)
                ->exists();

            // Locking logic: The first bab (index 0) is always unlocked. 
            // Others depend on the PREVIOUS bab's kuis pass status.
            if ($index > 0) {
                $previousBab = $mapel->babs[$index - 1];
                
                $passedPrevious = HasilUjian::where('siswa_id', $siswaId)
                    ->whereHas('ujian', function($q) use ($previousBab) {
                        $q->where('bab_id', $previousBab->id)->where('tipe', 'kuis');
                    })
                    ->where('is_lulus', true)
                    ->exists();

                if (!$passedPrevious) {
                    $isLocked = true;
                }
            }

            $bab->is_locked = $isLocked;
            return $bab;
        });

        return view('siswa.mapel.show', compact('mapel', 'babs'));
    }
}
