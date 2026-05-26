<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\Mapel;
use App\Models\Bab;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function create(Request $request)
    {
        $mapels = Mapel::where('guru_id', auth()->id())->with('babs')->get();
        $selectedBab = $request->query('bab_id');
        $selectedMapel = $request->query('mapel_id');
        return view('guru.ujian.create', compact('mapels', 'selectedBab', 'selectedMapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:kuis,uts,uas',
            'mapel_id' => 'required|exists:mapels,id',
            'bab_id' => 'nullable|exists:babs,id',
            'jumlah_soal_tampil' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'mulai_pada' => 'nullable|date',
            'selesai_pada' => 'nullable|date|after_or_equal:mulai_pada',
        ]);

        $ujian = Ujian::create($request->all());

        return redirect()->route('guru.ujians.show', $ujian->id)->with('success', 'Ujian/Bank Soal berhasil dibuat. Silakan tambahkan soal.');
    }

    public function show($id)
    {
        $ujian = Ujian::with('soals')->findOrFail($id);
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);
        
        return view('guru.ujian.show', compact('ujian'));
    }

    public function edit($id)
    {
        $ujian = Ujian::findOrFail($id);
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);
        
        $mapels = Mapel::where('guru_id', auth()->id())->with('babs')->get();
        return view('guru.ujian.edit', compact('ujian', 'mapels'));
    }

    public function update(Request $request, $id)
    {
        $ujian = Ujian::findOrFail($id);
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tipe' => 'required|in:kuis,uts,uas',
            'jumlah_soal_tampil' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'mulai_pada' => 'nullable|date',
            'selesai_pada' => 'nullable|date|after_or_equal:mulai_pada',
        ]);

        $ujian->update($request->all());

        return redirect()->route('guru.ujians.show', $ujian->id)->with('success', 'Pengaturan Ujian diperbarui.');
    }

    public function destroy($id)
    {
        $ujian = Ujian::findOrFail($id);
        if ($ujian->mapel->guru_id !== auth()->id()) abort(403);
        
        $mapel_id = $ujian->mapel_id;
        $ujian->delete();
        
        return redirect()->route('guru.babs.index', $mapel_id)->with('success', 'Ujian beserta soalnya berhasil dihapus.');
    }
    public function index()
    {
        $ujians = Ujian::whereHas('mapel', function($q) {
            $q->where('guru_id', auth()->id());
        })->with('mapel')->get();

        return view('guru.ujian.index', compact('ujians'));
    }

}
