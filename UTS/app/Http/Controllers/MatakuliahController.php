<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MatakuliahController extends Controller
{
    public function index()
    {
        $matakuliah = Matakuliah::with('dosen:id,nama')
            ->orderBy('semester')
            ->orderBy('kode_mk')
            ->get();

        return Inertia::render('Matakuliah/Index', [
            'matakuliah' => $matakuliah->map(fn ($m) => [
                'id' => $m->id,
                'kode_mk' => $m->kode_mk,
                'nama_mk' => $m->nama_mk,
                'deskripsi' => $m->deskripsi,
                'sks' => $m->sks,
                'semester' => $m->semester,
                'jenis' => $m->jenis,
                'pengampu' => $m->dosen?->nama,
            ])->values(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Matakuliah/Form', ['matakuliah' => null] + $this->formOptions());
    }

    public function store(Request $request)
    {
        $matakuliah = Matakuliah::create($this->validated($request));

        return redirect()->route('matakuliah.index')
            ->with('success', "Mata kuliah {$matakuliah->nama_mk} berhasil ditambahkan.");
    }

    public function show(Matakuliah $matakuliah)
    {
        $matakuliah->load('dosen');

        return Inertia::render('Matakuliah/Show', [
            'matakuliah' => [
                'id' => $matakuliah->id,
                'kode_mk' => $matakuliah->kode_mk,
                'nama_mk' => $matakuliah->nama_mk,
                'deskripsi' => $matakuliah->deskripsi,
                'sks' => $matakuliah->sks,
                'semester' => $matakuliah->semester,
                'jenis' => $matakuliah->jenis,
                'dibuat' => $matakuliah->created_at->translatedFormat('d F Y'),
                'diperbarui' => $matakuliah->updated_at->diffForHumans(),
                'dosen' => $matakuliah->dosen ? [
                    'id' => $matakuliah->dosen->id,
                    'nama' => $matakuliah->dosen->nama,
                    'status_dosen' => $matakuliah->dosen->status_dosen,
                    'pendidikan_label' => $matakuliah->dosen->pendidikan_label,
                ] : null,
            ],
        ]);
    }

    public function edit(Matakuliah $matakuliah)
    {
        return Inertia::render('Matakuliah/Form', [
            'matakuliah' => $matakuliah->only(['id', 'kode_mk', 'nama_mk', 'sks', 'semester', 'jenis', 'dosen_id', 'deskripsi']),
        ] + $this->formOptions());
    }

    public function update(Request $request, Matakuliah $matakuliah)
    {
        $matakuliah->update($this->validated($request, $matakuliah));

        return redirect()->route('matakuliah.index')
            ->with('success', "Mata kuliah {$matakuliah->nama_mk} berhasil diperbarui.");
    }

    public function destroy(Matakuliah $matakuliah)
    {
        $matakuliah->delete();

        return redirect()->route('matakuliah.index')
            ->with('success', "Mata kuliah {$matakuliah->nama_mk} berhasil dihapus.");
    }

    private function formOptions(): array
    {
        return [
            'dosen' => Dosen::orderBy('nama')->get(['id', 'nama'])
                ->map(fn ($d) => ['value' => $d->id, 'label' => $d->nama])->values(),
            'jenis' => Matakuliah::JENIS,
        ];
    }

    private function validated(Request $request, ?Matakuliah $matakuliah = null): array
    {
        return $request->validate([
            'kode_mk' => ['required', 'string', 'max:20', Rule::unique('tbl_matakuliah', 'kode_mk')->ignore($matakuliah)],
            'nama_mk' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'between:1,6'],
            'semester' => ['required', 'integer', 'between:1,8'],
            'jenis' => ['required', Rule::in(Matakuliah::JENIS)],
            'dosen_id' => ['nullable', 'exists:tbl_dosen,id'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [], [
            'kode_mk' => 'kode mata kuliah',
            'nama_mk' => 'nama mata kuliah',
            'sks' => 'SKS',
            'dosen_id' => 'dosen pengampu',
        ]);
    }
}
