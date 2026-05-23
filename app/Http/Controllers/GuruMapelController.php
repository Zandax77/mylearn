<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

class GuruMapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::where('guru_id', auth()->id())->with('kelas')->get();
        return view('guru.mapel.index', compact('mapels'));
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        if ($mapel->guru_id !== auth()->id()) abort(403);
        
        $kelas = Kelas::all();
        $assignedKelas = $mapel->kelas->pluck('id')->toArray();
        
        return view('guru.mapel.assign', compact('mapel', 'kelas', 'assignedKelas'));
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        if ($mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'kelas_id' => 'nullable|array',
            'kelas_id.*' => 'exists:kelas,id',
        ]);

        $mapel->kelas()->sync($request->kelas_id ?? []);

        return redirect()->route('guru.mapel.index')->with('success', 'Kelas berhasil diatur untuk mata pelajaran ini.');
    }
}
