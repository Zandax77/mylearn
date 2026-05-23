<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Soal;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiswaUjianController extends Controller
{
    public function show($ujian_id)
    {
        $ujian = Ujian::with('soals')->findOrFail($ujian_id);
        $siswaId = auth()->id();

        // Check if student has already passed
        $alreadyPassed = HasilUjian::where('siswa_id', $siswaId)
            ->where('ujian_id', $ujian->id)
            ->where('is_lulus', true)
            ->exists();

        // Randomize questions from the bank
        $jumlahSoal = $ujian->jumlah_soal_tampil;
        
        // Check session to see if an exam is already in progress to keep same questions
        $sessionKey = "ujian_{$ujian->id}_questions";
        if (Session::has($sessionKey)) {
            $soalIds = Session::get($sessionKey);
            $soals = Soal::whereIn('id', $soalIds)->get();
        } else {
            $soals = $ujian->soals()->inRandomOrder()->limit($jumlahSoal)->get();
            if ($soals->count() < $jumlahSoal) {
                return back()->with('error', 'Bank soal tidak mencukupi untuk memulai kuis. Hubungi guru Anda.');
            }
            Session::put($sessionKey, $soals->pluck('id')->toArray());
            Session::put("ujian_{$ujian->id}_start", now());
        }

        $durasi = $soals->count(); // 1 minute per question
        
        return view('siswa.ujian.show', compact('ujian', 'soals', 'durasi', 'alreadyPassed'));
    }

    public function submit(Request $request, $ujian_id)
    {
        $ujian = Ujian::with('soals')->findOrFail($ujian_id);
        $soalIds = Session::get("ujian_{$ujian_id}_questions");
        
        if (!$soalIds) {
            return redirect()->route('siswa.mapel.show', $ujian->mapel_id)->with('error', 'Sesi ujian berakhir atau tidak valid.');
        }

        $soals = Soal::whereIn('id', $soalIds)->get();
        $jawabanSiswa = $request->input('jawaban', []);
        
        $jumlahBenar = 0;
        foreach ($soals as $soal) {
            if (isset($jawabanSiswa[$soal->id]) && $jawabanSiswa[$soal->id] == $soal->jawaban_benar) {
                $jumlahBenar++;
            }
        }

        $nilai = ($soals->count() > 0) ? ($jumlahBenar / $soals->count()) * 100 : 0;
        $isLulus = $nilai >= 80;

        HasilUjian::create([
            'siswa_id' => auth()->id(),
            'ujian_id' => $ujian->id,
            'nilai' => $nilai,
            'is_lulus' => $isLulus,
            'completed_at' => now(),
        ]);

        // Clear session
        Session::forget("ujian_{$ujian_id}_questions");
        Session::forget("ujian_{$ujian_id}_start");

        return view('siswa.ujian.result', compact('ujian', 'nilai', 'isLulus', 'jumlahBenar', 'soals'));
    }
}
