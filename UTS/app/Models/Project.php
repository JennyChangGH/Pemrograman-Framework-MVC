<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $table = 'tbl_project';

    protected $fillable = [
        'judul',
        'nama_mahasiswa',
        'nim',
        'angkatan',
        'kategori',
        'status',
        'dosen_id',
        'link_repo',
        'deskripsi',
    ];

    protected $casts = [
        'angkatan' => 'integer',
    ];

    public const KATEGORI = [
        'Web Development',
        'Mobile App',
        'Game Development',
        'Artificial Intelligence',
        'Internet of Things',
        'Sistem Informasi',
    ];

    public const STATUS = ['Proposal', 'Berjalan', 'Selesai'];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
