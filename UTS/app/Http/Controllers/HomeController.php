<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Project;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'dosen' => Dosen::count(),
            'project' => Project::count(),
            'matakuliah' => Matakuliah::count(),
            'sks' => (int) Matakuliah::sum('sks'),
        ];

        $projectStatus = collect(Project::STATUS)
            ->mapWithKeys(fn ($status) => [$status => Project::where('status', $status)->count()]);

        $projects = Project::with('dosen:id,nama')->latest()->take(9)->get();
        $dosen = Dosen::withCount('project')->orderBy('nama')->take(8)->get();
        $matakuliah = Matakuliah::with('dosen:id,nama')->orderBy('semester')->orderBy('kode_mk')->get();

        return Inertia::render('LandingPage', [
            'stats' => $stats,
            'projectStatus' => $projectStatus,
            'projects' => $projects->map(fn ($p) => [
                'judul' => $p->judul,
                'nama_mahasiswa' => $p->nama_mahasiswa,
                'angkatan' => $p->angkatan,
                'kategori' => $p->kategori,
                'status' => $p->status,
                'deskripsi' => $p->deskripsi,
                'pembimbing' => $p->dosen?->nama,
            ])->values(),
            'dosen' => $dosen->map(fn ($d) => [
                'nama' => $d->nama,
                'status_dosen' => $d->status_dosen,
                'pendidikan' => $d->pendidikan_label,
                'project_count' => $d->project_count,
            ])->values(),
            'matakuliah' => $matakuliah->map(fn ($m) => [
                'kode_mk' => $m->kode_mk,
                'nama_mk' => $m->nama_mk,
                'sks' => $m->sks,
                'semester' => $m->semester,
                'jenis' => $m->jenis,
                'pengampu' => $m->dosen?->nama,
            ])->values(),
        ]);
    }
}
