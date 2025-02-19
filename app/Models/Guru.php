<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model {
    use HasFactory;

    protected $fillable = ['nip', 'nama', 'mapel'];

    // Many-to-Many dengan Kelas
    public function kelas() {
        return $this->belongsToMany(Kelas::class, 'guru_kelas', 'guru_id', 'kelas_id')->withTimestamps();
    }
}

