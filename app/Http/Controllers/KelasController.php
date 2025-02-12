<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $kelas = Kelas::with(['siswas', 'gurus'])->get();
            return response()->json($kelas);
        }
        return view('kelas.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
            'guru_id' => 'nullable|exists:gurus,id|unique:kelas,guru_id', // Pastikan hanya satu guru per kelas
        ]);

        $kelas = Kelas::create($request->only('nama', 'guru_id'));

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan!',
            'kelas' => $kelas
        ]);
    }

    public function show(Kelas $kelas)
    {
        if (request()->ajax()) {
            return response()->json($kelas->load(['siswas', 'gurus']));
        }
        return view('kelas.show', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $kelas->id,
            'guru_id' => 'nullable|exists:gurus,id|unique:kelas,guru_id,' . $kelas->id, // Pastikan hanya satu guru per kelas
        ]);

        $kelas->update($request->only('nama', 'guru_id'));

        return response()->json([
            'message' => 'Kelas berhasil diperbarui!',
            'kelas' => $kelas
        ]);
    }

    public function destroy(Kelas $kelas)
    {
        // Hapus semua siswa yang terkait dengan kelas
        $kelas->siswas()->delete();

        // Hapus semua guru yang terkait dengan kelas
        $kelas->gurus()->delete();

        // Hapus kelas setelah semua relasi dihapus
        $kelas->delete();

        return response()->json([
            'message' => 'Kelas beserta semua guru dan siswa telah berhasil dihapus!'
        ]);
    }
}


