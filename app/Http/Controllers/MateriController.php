<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::whereHas('mapel', function($q) {
            $q->where('guru_id', auth()->id());
        })->with('mapel')->latest()->get();
        
        return view('guru.materi.index', compact('materis'));
    }

    public function create(Request $request)
    {
        $mapels = Mapel::where('guru_id', auth()->id())->with('babs')->get();
        $selectedBab = $request->query('bab_id');
        return view('guru.materi.create', compact('mapels', 'selectedBab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mapel_id' => 'required|exists:mapels,id',
            'bab_id' => 'required|exists:babs,id',
            'urutan' => 'required|integer|min:1',
            'file' => 'required|file|mimes:pdf,ppt,pptx,doc,docx,zip|max:10240',
        ]);

        $path = $request->file('file')->store('materis', 'public');

        Materi::create([
            'judul' => $request->judul,
            'mapel_id' => $request->mapel_id,
            'bab_id' => $request->bab_id,
            'urutan' => $request->urutan,
            'file' => $path,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('guru.babs.index', $request->mapel_id)->with('success', 'Materi berhasil diunggah.');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        
        if ($materi->mapel->guru_id !== auth()->id()) {
            abort(403);
        }

        if ($materi->file) {
            Storage::disk('public')->delete($materi->file);
        }
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function download($id)
    {
        $materi = Materi::findOrFail($id);
        
        if (auth()->user()->role === 'siswa') {
            \App\Models\ProgresMateri::firstOrCreate([
                'siswa_id' => auth()->id(),
                'materi_id' => $materi->id,
            ]);
        }

        return Storage::disk('public')->download($materi->file);
    }

    public function viewMateri($id)
    {
        $materi = Materi::findOrFail($id);
        
        if (auth()->user()->role === 'siswa') {
            \App\Models\ProgresMateri::firstOrCreate([
                'siswa_id' => auth()->id(),
                'materi_id' => $materi->id,
            ]);
        }

        $extension = pathinfo($materi->file, PATHINFO_EXTENSION);
        return view('siswa.materi.view', compact('materi', 'extension'));
    }

    public function indexSiswa()
    {
        $kelasId = auth()->user()->kelas_id;
        $materis = Materi::whereHas('mapel.kelas', function($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId);
        })->with('mapel.guru')->latest()->get();
        return view('siswa.materi.index', compact('materis'));
    }
}
