<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function getData()
    {
        $kelas = Kelas::with(['gurus', 'siswas'])->get();

        return response()->json([
            'kelas' => $kelas
        ]);
    }
}

