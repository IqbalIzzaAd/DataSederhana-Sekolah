<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {
    use HasFactory;
    
    protected $fillable = ['nama'];

    public function gurus() {
        return $this->belongsToMany(Guru::class, 'guru_kelas', 'kelas_id', 'guru_id')->withTimestamps();
    }

    public function siswas() { // Ganti dari hasOne ke hasMany
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    protected static function boot() {
        parent::boot();
        static::updating(function ($kelas) {
            $kelas->gurus()->detach(); // Hapus semua guru dari kelas

            // Set semua siswa dalam kelas ini menjadi tanpa kelas (kelas_id = null)
            $kelas->siswas()->update(['kelas_id' => null]);
        });
    }
}