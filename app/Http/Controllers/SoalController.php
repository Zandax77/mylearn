<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\Ujian;
use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function create(Ujian $ujian)
    {
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);
        return view('guru.soal.create', compact('ujian'));
    }

    public function store(Request $request, Ujian $ujian)
    {
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $ujian->soals()->create($request->all());

        return redirect()->route('guru.ujians.show', $ujian->id)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Soal $soal)
    {
        if ($soal->ujian->mapel->guru_id !== auth()->id()) abort(403);
        return view('guru.soal.edit', compact('soal'));
    }

    public function update(Request $request, Soal $soal)
    {
        if ($soal->ujian->mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'jawaban_benar' => 'required|in:a,b,c,d',
        ]);

        $soal->update($request->all());

        return redirect()->route('guru.ujians.show', $soal->ujian_id)->with('success', 'Soal diperbarui.');
    }

    public function destroy(Soal $soal)
    {
        if ($soal->ujian->mapel->guru_id !== auth()->id()) abort(403);
        $ujian_id = $soal->ujian_id;
        $soal->delete();
        return redirect()->route('guru.ujians.show', $ujian_id)->with('success', 'Soal dihapus.');
    }
}
