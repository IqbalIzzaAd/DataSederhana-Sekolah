<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->unique(); // NIP tetap unik secara global
            $table->string('mapel'); // Mata pelajaran
            $table->timestamps();
        });

        // Tabel pivot untuk hubungan Many-to-Many antara Guru & Kelas
        Schema::create('guru_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();

            // Menghindari duplikasi guru dalam satu kelas berdasarkan NIP
            $table->unique(['guru_id', 'kelas_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('guru_kelas');
        Schema::dropIfExists('gurus');
    }
};

