<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request) {
        $kelas = Kelas::all();
        $query = Siswa::query()->with('kelas');
    
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }
    
        $siswas = $query->get();
        return view('siswa.index', compact('siswas', 'kelas'));
    }

    public function create() {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:siswas,nama',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->only(['nama', 'kelas_id']));
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa) {
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa) {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:siswas,nama,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($request->only(['nama', 'kelas_id']));
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa) {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
    
}
