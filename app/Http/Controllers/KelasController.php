<?php
namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return response()->json(Kelas::all(), 200);
        }
        return view('kelas.index'); 
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
        ]);

        $kelas = Kelas::create($request->only('nama'));

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan!',
            'kelas' => $kelas
        ], 201);
    }

    public function show(Kelas $kelas)
    {
        if (request()->ajax()) {
            return response()->json($kelas, 200);
        }
        return view('kelas.show', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $kelas->id,
        ]);

        $kelas->update($request->only('nama'));

        return response()->json([
            'message' => 'Kelas berhasil diperbarui!',
            'kelas' => $kelas
        ], 200);
    }

    public function destroy(Kelas $kelas)
    {
        try {
            $kelas->delete();
            return response()->json(['message' => 'Kelas berhasil dihapus!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus kelas!'], 500);
        }
    }
}
