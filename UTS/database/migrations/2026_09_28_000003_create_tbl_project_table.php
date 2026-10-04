<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_project', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('nama_mahasiswa', 100);
            $table->string('nim', 20);
            $table->year('angkatan');
            $table->string('kategori', 50);
            $table->enum('status', ['Proposal', 'Berjalan', 'Selesai'])->default('Proposal');
            $table->foreignId('dosen_id')->nullable()->constrained('tbl_dosen')->nullOnDelete();
            $table->string('link_repo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_project');
    }
};
