<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matakuliah extends Model
{
    protected $table = 'tbl_matakuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'dosen_id',
        'deskripsi',
    ];

    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
    ];

    public const JENIS = ['Wajib', 'Pilihan'];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
