<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::withCount(['matakuliah', 'project'])->latest()->get();

        return Inertia::render('Dosen/Index', [
            'dosen' => $dosen->map(fn ($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
                'email' => $d->email,
                'nidn' => $d->nidn,
                'status_dosen' => $d->status_dosen,
                'pendidikan_terakhir' => $d->pendidikan_terakhir,
                'matakuliah_count' => $d->matakuliah_count,
                'project_count' => $d->project_count,
            ])->values(),
            'status' => Dosen::STATUS,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dosen/Form', ['dosen' => null] + $this->formOptions());
    }

    public function store(Request $request)
    {
        $dosen = Dosen::create($this->validated($request));

        return redirect()->route('dosen.index')
            ->with('success', "Dosen {$dosen->nama} berhasil ditambahkan.");
    }

    public function show(Dosen $dosen)
    {
        $dosen->load(['matakuliah', 'project']);

        return Inertia::render('Dosen/Show', [
            'dosen' => [
                'id' => $dosen->id,
                'nama' => $dosen->nama,
                'nidn' => $dosen->nidn,
                'email' => $dosen->email,
                'no_hp' => $dosen->no_hp,
                'status_dosen' => $dosen->status_dosen,
                'pendidikan_label' => $dosen->pendidikan_label,
                'terdaftar' => $dosen->created_at->translatedFormat('d F Y'),
                'matakuliah' => $dosen->matakuliah->map(fn ($m) => [
                    'id' => $m->id,
                    'kode_mk' => $m->kode_mk,
                    'nama_mk' => $m->nama_mk,
                    'semester' => $m->semester,
                    'sks' => $m->sks,
                ])->values(),
                'project' => $dosen->project->map(fn ($p) => [
                    'id' => $p->id,
                    'judul' => $p->judul,
                    'nama_mahasiswa' => $p->nama_mahasiswa,
                    'kategori' => $p->kategori,
                    'status' => $p->status,
                ])->values(),
            ],
        ]);
    }

    public function edit(Dosen $dosen)
    {
        return Inertia::render('Dosen/Form', [
            'dosen' => $dosen->only(['id', 'nidn', 'nama', 'email', 'no_hp', 'status_dosen', 'pendidikan_terakhir']),
        ] + $this->formOptions());
    }

    public function update(Request $request, Dosen $dosen)
    {
        $dosen->update($this->validated($request, $dosen));

        return redirect()->route('dosen.index')
            ->with('success', "Data dosen {$dosen->nama} berhasil diperbarui.");
    }

    public function destroy(Dosen $dosen)
    {
        $jumlahMk = $dosen->matakuliah()->count();
        $jumlahProject = $dosen->project()->count();

        if ($jumlahMk > 0 || $jumlahProject > 0) {
            $alasan = [];
            if ($jumlahMk > 0) {
                $alasan[] = "dosen pengampu {$jumlahMk} mata kuliah";
            }
            if ($jumlahProject > 0) {
                $alasan[] = "dosen pembimbing {$jumlahProject} project";
            }

            return redirect()->route('dosen.index')
                ->with('error', "Dosen {$dosen->nama} tidak dapat dihapus karena masih menjadi ".implode(' dan ', $alasan).'.');
        }

        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', "Dosen {$dosen->nama} berhasil dihapus.");
    }

    private function formOptions(): array
    {
        return [
            'status' => Dosen::STATUS,
            'pendidikan' => Dosen::PENDIDIKAN,
        ];
    }

    private function validated(Request $request, ?Dosen $dosen = null): array
    {
        return $request->validate([
            'nidn' => ['required', 'string', 'max:20', Rule::unique('tbl_dosen', 'nidn')->ignore($dosen)],
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbl_dosen', 'email')->ignore($dosen)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'status_dosen' => ['required', Rule::in(Dosen::STATUS)],
            'pendidikan_terakhir' => ['required', Rule::in(array_keys(Dosen::PENDIDIKAN))],
        ], [], [
            'nidn' => 'NIDN',
            'no_hp' => 'nomor HP',
            'status_dosen' => 'status dosen',
            'pendidikan_terakhir' => 'pendidikan terakhir',
        ]);
    }
}
