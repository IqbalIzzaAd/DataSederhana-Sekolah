<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model {
    use HasFactory;
    protected $fillable = ['nama'];

    public function siswas() {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    public function gurus() {
        return $this->belongsToMany(Guru::class, 'guru_kelas', 'kelas_id', 'guru_id');
    }
}

