<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Soal;
use App\Models\BankSoal;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiswaUjianController extends Controller
{
    public function show($ujian_id)
    {
        $ujian = Ujian::with('soals')->findOrFail($ujian_id);
        $siswaId = auth()->id();

        $now = now();
        if ($ujian->mulai_pada && $now->lt($ujian->mulai_pada)) {
            return back()->with('error', 'Ujian belum dibuka. Ujian dijadwalkan mulai pada ' . $ujian->mulai_pada->translatedFormat('d F Y H:i'));
        }
        if ($ujian->selesai_pada && $now->gt($ujian->selesai_pada)) {
            return back()->with('error', 'Waktu ujian telah berakhir. Ujian ditutup pada ' . $ujian->selesai_pada->translatedFormat('d F Y H:i'));
        }

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
            if ($ujian->tipe === 'kuis') {
                $soals = Soal::whereIn('id', $soalIds)->get();
            } else {
                $soals = BankSoal::whereIn('id', $soalIds)->get();
            }
        } else {
            if ($ujian->tipe === 'kuis') {
                $soals = $ujian->soals()->inRandomOrder()->limit($jumlahSoal)->get();
            } else {
                // UAS/UTS: Pull from BankSoal for the entire mapel
                $query = BankSoal::where('mapel_id', $ujian->mapel_id);
                if ($ujian->bab_id) {
                    $query->where('bab_id', $ujian->bab_id);
                }
                $soals = $query->inRandomOrder()->limit($jumlahSoal)->get();
            }

            if ($soals->count() < $jumlahSoal) {
                return back()->with('error', 'Bank soal tidak mencukupi untuk memulai ujian. Hubungi guru Anda.');
            }
            Session::put($sessionKey, $soals->pluck('id')->toArray());
            Session::put("ujian_{$ujian->id}_start", now());
        }

        $durasi = $ujian->durasi_menit ?? $soals->count(); 
        
        return view('siswa.ujian.show', compact('ujian', 'soals', 'durasi', 'alreadyPassed'));
    }

    public function submit(Request $request, $ujian_id)
    {
        $ujian = Ujian::with('soals')->findOrFail($ujian_id);
        $soalIds = Session::get("ujian_{$ujian_id}_questions");
        
        if (!$soalIds) {
            return redirect()->route('siswa.mapel.show', $ujian->mapel_id)->with('error', 'Sesi ujian berakhir atau tidak valid.');
        }

        $jawabanSiswa = $request->input('jawaban', []);
        $jumlahBenar = 0;

        if ($ujian->tipe === 'kuis') {
            $soals = Soal::whereIn('id', $soalIds)->get();
            foreach ($soals as $soal) {
                if (isset($jawabanSiswa[$soal->id]) && $jawabanSiswa[$soal->id] == $soal->jawaban_benar) {
                    $jumlahBenar++;
                }
            }
        } else {
            $soals = BankSoal::whereIn('id', $soalIds)->get();
            foreach ($soals as $soal) {
                if (!isset($jawabanSiswa[$soal->id])) continue;
                $jwbn = $jawabanSiswa[$soal->id];
                
                if ($soal->tipe_soal === 'pg' || $soal->tipe_soal === 'bs') {
                    $benar = $soal->jawaban_benar[0] ?? null;
                    if ($jwbn == $benar) {
                        $jumlahBenar++;
                    }
                } elseif ($soal->tipe_soal === 'jodoh') {
                    $benar = $soal->jawaban_benar;
                    $isCorrect = true;
                    if (is_array($benar) && is_array($jwbn)) {
                        foreach ($benar as $key => $val) {
                            if (!isset($jwbn[$key]) || strtolower(trim($jwbn[$key])) !== strtolower(trim($val))) {
                                $isCorrect = false;
                                break;
                            }
                        }
                    } else {
                        $isCorrect = false;
                    }
                    if ($isCorrect) {
                        $jumlahBenar++;
                    }
                }
            }
        }

        $nilai = ($soals->count() > 0) ? ($jumlahBenar / $soals->count()) * 100 : 0;
        $isLulus = $nilai >= $ujian->passing_grade;

        HasilUjian::create([
            'siswa_id' => auth()->id(),
            'ujian_id' => $ujian->id,
            'nilai' => $nilai,
            'is_lulus' => $isLulus,
            'completed_at' => now(),
        ]);

        Session::forget("ujian_{$ujian_id}_questions");
        Session::forget("ujian_{$ujian_id}_start");

        return view('siswa.ujian.result', compact('ujian', 'nilai', 'isLulus', 'jumlahBenar', 'soals'));
    }
}
