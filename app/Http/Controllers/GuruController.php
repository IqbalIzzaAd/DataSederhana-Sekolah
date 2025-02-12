<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request) {
        $kelas = Kelas::all();
        $query = Guru::with('kelas');
    
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->whereHas('kelas', function($q) use ($request) {
                $q->where('kelas.id', $request->kelas_id);
            });
        }
    
        $gurus = $query->get();
        return view('guru.index', compact('gurus', 'kelas'));
    }
    

    public function create() {
        $kelas = Kelas::all();
        return view('guru.create', compact('kelas'));
    }

    public function store(Request $request) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:gurus,nama',
            'kelas_id' => 'array',
        ]);

        $guru = Guru::create($request->only('nama'));
        if ($request->has('kelas_id')) {
            $guru->kelas()->sync($request->kelas_id);
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function show(Guru $guru) {
        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru) {
        $kelas = Kelas::all();
        return view('guru.edit', compact('guru', 'kelas'));
    }

    public function update(Request $request, Guru $guru) {
        // Tambahkan validasi input
        $request->validate([
            'nama' => 'required|string|max:255|unique:gurus,nama,' . $guru->id,
            'kelas_id' => 'array',
        ]);

        $guru->update($request->only('nama'));
        if ($request->has('kelas_id')) {
            $guru->kelas()->sync($request->kelas_id);
        }

        return redirect()->route('guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru) {
        // Cek apakah guru masih memiliki hubungan dengan kelas sebelum menghapus
        if ($guru->kelas()->exists()) {
            return redirect()->route('guru.index')->with('error', 'Guru tidak bisa dihapus karena masih terkait dengan kelas.');
        }

        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
