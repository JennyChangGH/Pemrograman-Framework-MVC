<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $project = Project::with('dosen:id,nama')->latest()->get();

        return Inertia::render('Project/Index', [
            'project' => $project->map(fn ($p) => [
                'id' => $p->id,
                'judul' => $p->judul,
                'kategori' => $p->kategori,
                'nama_mahasiswa' => $p->nama_mahasiswa,
                'nim' => $p->nim,
                'nim_angkatan' => "{$p->nim} · Angkatan {$p->angkatan}",
                'pembimbing' => $p->dosen?->nama,
                'status' => $p->status,
            ])->values(),
            'status' => Project::STATUS,
        ]);
    }

    public function create()
    {
        return Inertia::render('Project/Form', ['project' => null] + $this->formOptions());
    }

    public function store(Request $request)
    {
        $project = Project::create($this->validated($request));

        return redirect()->route('project.index')
            ->with('success', "Project \"{$project->judul}\" berhasil ditambahkan.");
    }

    public function show(Project $project)
    {
        $project->load('dosen');

        return Inertia::render('Project/Show', [
            'project' => [
                'id' => $project->id,
                'judul' => $project->judul,
                'nama_mahasiswa' => $project->nama_mahasiswa,
                'nim' => $project->nim,
                'angkatan' => $project->angkatan,
                'kategori' => $project->kategori,
                'status' => $project->status,
                'link_repo' => $project->link_repo,
                'deskripsi' => $project->deskripsi,
                'dibuat' => $project->created_at->translatedFormat('d F Y'),
                'diperbarui' => $project->updated_at->diffForHumans(),
                'dosen' => $project->dosen ? [
                    'id' => $project->dosen->id,
                    'nama' => $project->dosen->nama,
                    'status_dosen' => $project->dosen->status_dosen,
                    'pendidikan_terakhir' => $project->dosen->pendidikan_terakhir,
                ] : null,
            ],
            'steps' => Project::STATUS,
        ]);
    }

    public function edit(Project $project)
    {
        return Inertia::render('Project/Form', [
            'project' => $project->only(['id', 'judul', 'nama_mahasiswa', 'nim', 'angkatan', 'kategori', 'status', 'dosen_id', 'link_repo', 'deskripsi']),
        ] + $this->formOptions());
    }

    public function update(Request $request, Project $project)
    {
        $project->update($this->validated($request));

        return redirect()->route('project.index')
            ->with('success', "Project \"{$project->judul}\" berhasil diperbarui.");
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('project.index')
            ->with('success', "Project \"{$project->judul}\" berhasil dihapus.");
    }

    private function formOptions(): array
    {
        return [
            'dosen' => Dosen::orderBy('nama')->get(['id', 'nama'])
                ->map(fn ($d) => ['value' => $d->id, 'label' => $d->nama])->values(),
            'kategori' => Project::KATEGORI,
            'status' => Project::STATUS,
            'tahunMaks' => now()->year + 1,
            'tahunIni' => now()->year,
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'nama_mahasiswa' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:20'],
            'angkatan' => ['required', 'integer', 'between:2000,'.(now()->year + 1)],
            'kategori' => ['required', Rule::in(Project::KATEGORI)],
            'status' => ['required', Rule::in(Project::STATUS)],
            'dosen_id' => ['nullable', 'exists:tbl_dosen,id'],
            'link_repo' => ['nullable', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ], [], [
            'judul' => 'judul project',
            'nama_mahasiswa' => 'nama mahasiswa',
            'nim' => 'NIM',
            'dosen_id' => 'dosen pembimbing',
            'link_repo' => 'link repository',
        ]);
    }
}
