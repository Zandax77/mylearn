<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::with('guru')->latest()->get();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.mapel.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
        ]);

        Mapel::create($request->all());

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        $gurus = User::where('role', 'guru')->get();
        return view('admin.mapel.edit', compact('mapel', 'gurus'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'guru_id' => 'required|exists:users,id',
        ]);

        $mapel = Mapel::findOrFail($id);
        $mapel->update($request->all());

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
