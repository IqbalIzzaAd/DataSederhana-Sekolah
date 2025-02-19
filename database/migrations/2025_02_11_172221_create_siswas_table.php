<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique(); // NIS tetap unik global
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null'); // Kelas bisa dihapus tanpa menghapus siswa
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('siswas');
    }
};
