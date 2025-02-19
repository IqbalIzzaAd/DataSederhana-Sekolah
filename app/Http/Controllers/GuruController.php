<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all(); // Ambil semua kelas untuk form
        return view('guru.index', compact('kelas'));
    }

    public function getGurus()
    {
        return response()->json(Guru::with('kelas')->get()); // Ambil guru beserta kelasnya (AJAX)
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'   => 'required|string|max:20|unique:gurus,nip',
            'nama'  => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
            'kelas' => 'array|exists:kelas,id'
        ]);

        $guru = Guru::create($request->only('nip', 'nama', 'mapel'));

        if ($request->has('kelas')) {
            $guru->kelas()->sync($request->kelas);
        }

        return response()->json([
            'message' => 'Guru berhasil ditambahkan!',
            'guru' => $guru->load('kelas') // Load kelas agar data terbaru langsung muncul
        ], 201);
    }

    public function show(Guru $guru)
    {
        return response()->json($guru->load('kelas'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip'   => 'required|string|max:20|unique:gurus,nip,' . $guru->id,
            'nama'  => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
            'kelas' => 'array|exists:kelas,id'
        ]);

        $guru->update($request->only('nip', 'nama', 'mapel'));

        if ($request->has('kelas')) {
            $guru->kelas()->sync($request->kelas);
        }

        return response()->json([
            'message' => 'Data guru berhasil diperbarui!',
            'guru' => $guru->load('kelas')
        ]);
    }

    public function destroy(Guru $guru)
    {
        $guru->kelas()->detach();
        $guru->delete();

        return response()->json([
            'message' => 'Guru berhasil dihapus!'
        ]);
    }
}
