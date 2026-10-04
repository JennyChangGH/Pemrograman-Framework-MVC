<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_dosen', function (Blueprint $table) {
            $table->renameColumn('jabatan', 'status_dosen');
        });

        DB::table('tbl_dosen')->update(['status_dosen' => 'Dosen Tetap']);

        Schema::table('tbl_dosen', function (Blueprint $table) {
            $table->dropColumn('bidang_keahlian');
            $table->enum('pendidikan_terakhir', ['S1', 'S2', 'S3'])->default('S2')->after('status_dosen');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_dosen', function (Blueprint $table) {
            $table->dropColumn('pendidikan_terakhir');
            $table->string('bidang_keahlian', 100)->default('-')->after('status_dosen');
        });

        Schema::table('tbl_dosen', function (Blueprint $table) {
            $table->renameColumn('status_dosen', 'jabatan');
        });
    }
};
