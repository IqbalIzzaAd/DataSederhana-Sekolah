<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

        if ($request->ajax()) {
            return response()->json(['gurus' => $gurus]);
        }

        return view('guru.index', compact('gurus', 'kelas'));
    }

    public function create() {
        $kelas = Kelas::all();
        return view('guru.create', compact('kelas'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:gurus,nama',
                'kelas_id' => 'array',
            ]);

            // Cek apakah kelas sudah memiliki guru
            foreach ($validatedData['kelas_id'] as $kelasId) {
                $kelas = Kelas::find($kelasId);
                if ($kelas && $kelas->gurus()->exists()) {
                    return response()->json([
                        'error' => 'Kelas ' . $kelas->nama . ' sudah memiliki guru. Tidak bisa menambahkan guru lain.'
                    ], 400);
                }
            }

            $guru = Guru::create(['nama' => $validatedData['nama']]);

            if ($request->has('kelas_id')) {
                $guru->kelas()->sync($validatedData['kelas_id']);
            }

            return response()->json([
                'message' => 'Guru berhasil ditambahkan!',
                'guru' => $guru
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function update(Request $request, Guru $guru) {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:gurus,nama,' . $guru->id,
                'kelas_id' => 'array',
            ]);

            // Cek apakah kelas sudah memiliki guru lain
            foreach ($validatedData['kelas_id'] as $kelasId) {
                $kelas = Kelas::find($kelasId);
                if ($kelas && $kelas->gurus()->where('id', '!=', $guru->id)->exists()) {
                    return response()->json([
                        'error' => 'Kelas ' . $kelas->nama . ' sudah memiliki guru lain. Tidak bisa menambahkan guru ini.'
                    ], 400);
                }
            }

            $guru->update(['nama' => $validatedData['nama']]);

            if ($request->has('kelas_id')) {
                $guru->kelas()->sync($validatedData['kelas_id']);
            }

            return response()->json([
                'message' => 'Guru berhasil diperbarui!',
                'guru' => $guru
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Guru $guru) {
        // Hapus guru tetapi biarkan kelas tetap ada
        $guru->kelas()->detach(); // Hapus relasi dengan kelas
        $guru->delete(); // Hapus guru dari database

        return response()->json([
            'message' => 'Guru berhasil dihapus, kelas tetap ada.'
        ]);
    }

    public function show(Guru $guru) {
        if (request()->ajax()) {
            return response()->json($guru->load('kelas'));
        }

        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru) {
        $kelas = Kelas::all();

        if (request()->ajax()) {
            return response()->json([
                'guru' => $guru->load('kelas'),
                'kelas' => $kelas
            ]);
        }

        return view('guru.edit', compact('guru', 'kelas'));
    }
}



