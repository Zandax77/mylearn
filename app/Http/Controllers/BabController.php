<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Bab;
use Illuminate\Http\Request;

class BabController extends Controller
{
    public function index($mapel_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        if ($mapel->guru_id !== auth()->id()) abort(403);
        
        $babs = $mapel->babs()->with(['materis', 'ujians'])->orderBy('urutan')->get();
        return view('guru.bab.index', compact('mapel', 'babs'));
    }

    public function store(Request $request, $mapel_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        if ($mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
        ]);

        Bab::create([
            'mapel_id' => $mapel->id,
            'judul' => $request->judul,
            'urutan' => $request->urutan,
        ]);

        return back()->with('success', 'Bab berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $bab = Bab::findOrFail($id);
        if ($bab->mapel->guru_id !== auth()->id()) abort(403);

        $request->validate([
            'judul' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
        ]);

        $bab->update([
            'judul' => $request->judul,
            'urutan' => $request->urutan,
        ]);

        return back()->with('success', 'Bab berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bab = Bab::findOrFail($id);
        if ($bab->mapel->guru_id !== auth()->id()) abort(403);

        $bab->delete();
        return back()->with('success', 'Bab berhasil dihapus.');
    }
}
