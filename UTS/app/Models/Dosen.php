<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    protected $table = 'tbl_dosen';

    protected $fillable = [
        'nidn',
        'nama',
        'email',
        'no_hp',
        'status_dosen',
        'pendidikan_terakhir',
    ];

    public const STATUS = [
        'Dosen Tetap',
        'Dosen Tidak Tetap',
    ];

    public const PENDIDIKAN = [
        'S1' => 'Sarjana (S1)',
        'S2' => 'Magister (S2)',
        'S3' => 'Doktor (S3)',
    ];

    public function getPendidikanLabelAttribute(): string
    {
        return self::PENDIDIKAN[$this->pendidikan_terakhir] ?? (string) $this->pendidikan_terakhir;
    }

    public function matakuliah(): HasMany
    {
        return $this->hasMany(Matakuliah::class, 'dosen_id');
    }

    public function project(): HasMany
    {
        return $this->hasMany(Project::class, 'dosen_id');
    }
}
