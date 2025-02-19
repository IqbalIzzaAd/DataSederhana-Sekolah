<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
{
    if (request()->ajax()) {
        return response()->json(Siswa::with('kelas')->get()); // Ambil semua siswa beserta kelasnya
    }

    $kelas = Kelas::all(); // Ambil semua data kelas

    return view('siswa.index', compact('kelas')); // Kirim ke view
}


    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:255|unique:siswas,nis',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        $siswa = Siswa::create($request->only('nis', 'nama', 'kelas_id'));

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan!',
            'siswa' => $siswa->load('kelas') // Load kelas untuk menampilkan relasi
        ]);
    }

    public function show(Siswa $siswa)
    {
        return response()->json($siswa->load('kelas')); // Mengembalikan siswa beserta kelasnya
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|max:255|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id'
        ]);

        $siswa->update($request->only('nis', 'nama', 'kelas_id'));

        return response()->json([
            'message' => 'Siswa berhasil diperbarui!',
            'siswa' => $siswa->load('kelas') // Load kelas setelah update
        ]);
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return response()->json([
            'message' => 'Siswa berhasil dihapus!'
        ]);
    }
}
