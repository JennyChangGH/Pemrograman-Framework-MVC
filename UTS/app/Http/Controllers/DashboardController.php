<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Project;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'dosen' => Dosen::count(),
            'project' => Project::count(),
            'matakuliah' => Matakuliah::count(),
            'sks' => (int) Matakuliah::sum('sks'),
        ];

        $projectPerStatus = collect(Project::STATUS)
            ->mapWithKeys(fn ($status) => [$status => Project::where('status', $status)->count()]);

        $projectPerKategori = collect(Project::KATEGORI)
            ->mapWithKeys(fn ($kategori) => [$kategori => Project::where('kategori', $kategori)->count()]);

        $latestProjects = Project::with('dosen:id,nama')->latest()->take(5)->get();

        $topDosen = Dosen::withCount(['project', 'matakuliah'])
            ->orderByDesc('project_count')
            ->take(5)
            ->get();

        return Inertia::render('AdminDashboard', [
            'stats' => $stats,
            'projectPerStatus' => $projectPerStatus,
            'projectPerKategori' => $projectPerKategori,
            'latestProjects' => $latestProjects->map(fn ($p) => [
                'judul' => $p->judul,
                'nama_mahasiswa' => $p->nama_mahasiswa,
                'kategori' => $p->kategori,
                'status' => $p->status,
                'url' => route('project.show', $p, false),
            ])->values(),
            'topDosen' => $topDosen->map(fn ($d) => [
                'nama' => $d->nama,
                'keterangan' => "{$d->status_dosen} · {$d->pendidikan_terakhir}",
                'project_count' => $d->project_count,
                'url' => route('dosen.show', $d, false),
            ])->values(),
        ]);
    }
}
