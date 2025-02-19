<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login'); // Langsung masuk ke halaman login
});

Route::middleware(['auth', 'verified'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 // API untuk mendapatkan data dashboard (untuk AJAX)
 Route::get('/api/dashboard', [DashboardController::class, 'getData'])->name('api.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // **CRUD untuk Kelas, Guru, dan Siswa**
    Route::resource('kelas', KelasController::class)->parameters([
        'kelas' => 'kelas'
    ]);
    Route::resource('guru', GuruController::class);
    Route::get('/guru-data',[GuruController::class,'getGurus'])->name('guru.getGurus');
    Route::resource('siswa', SiswaController::class);
});

require __DIR__.'/auth.php';
