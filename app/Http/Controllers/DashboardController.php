<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;


class DashboardController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['gurus', 'siswas'])->get();
        return view('dashboard', compact('kelas'));
    }
    public function getData()
    {
        $kelas = Kelas::with(['gurus', 'siswas'])->get();
        return response()->json([
            'kelas' => $kelas
        ]);
    }
}
