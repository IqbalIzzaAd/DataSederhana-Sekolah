<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SiswaController extends Controller
{
    public function index(Request $request) {
        $kelas = Kelas::all();
        $query = Siswa::query()->with('kelas');

        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $siswas = $query->get();

        if ($request->ajax()) {
            return response()->json(['siswas' => $siswas]);
        }

        return view('siswa.index', compact('siswas', 'kelas'));
    }

    public function create() {
        $kelas = Kelas::all();

        if (request()->ajax()) {
            return response()->json(['kelas' => $kelas]);
        }

        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:siswas,nama',
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            $siswa = Siswa::create($validatedData);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Siswa berhasil ditambahkan!',
                    'siswa' => $siswa
                ]);
            }

            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function show(Siswa $siswa) {
        if (request()->ajax()) {
            return response()->json($siswa->load('kelas'));
        }

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa) {
        $kelas = Kelas::all();

        if (request()->ajax()) {
            return response()->json([
                'siswa' => $siswa->load('kelas'),
                'kelas' => $kelas
            ]);
        }

        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa) {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255|unique:siswas,nama,' . $siswa->id,
                'kelas_id' => 'required|exists:kelas,id',
            ]);

            $siswa->update($validatedData);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Siswa berhasil diperbarui!',
                    'siswa' => $siswa
                ]);
            }

            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui.');
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function destroy(Siswa $siswa) {
        $siswa->delete();

        if (request()->ajax()) {
            return response()->json([
                'message' => 'Siswa berhasil dihapus!'
            ]);
        }

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}

