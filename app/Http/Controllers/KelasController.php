<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index() {
        $kelas = Kelas::with(['siswas', 'gurus'])->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create() {
        return view('kelas.create');
    }

    public function store(Request $request) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
        ]);

        Kelas::create($request->only('nama'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kelas) {
        return view('kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas) {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $kelas->id,
        ]);

        $kelas->update($request->only('nama'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas) {
        // Cek apakah kelas masih memiliki siswa atau guru sebelum menghapus
        if ($kelas->siswas()->exists() || $kelas->gurus()->exists()) {
            return redirect()->route('kelas.index')->with('error', 'Kelas tidak bisa dihapus karena masih memiliki siswa atau guru.');
        }

        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
