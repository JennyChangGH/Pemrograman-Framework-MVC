<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 20)->unique();
            $table->string('nama_mk', 100);
            $table->unsignedTinyInteger('sks');
            $table->unsignedTinyInteger('semester');
            $table->enum('jenis', ['Wajib', 'Pilihan'])->default('Wajib');
            $table->foreignId('dosen_id')->nullable()->constrained('tbl_dosen')->nullOnDelete();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_matakuliah');
    }
};
