<?php

namespace App\Http\Middleware;

use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => fn () => $request->user()
                ? ['user' => ['name' => $request->user()->name, 'email' => $request->user()->email]]
                : null,
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],

            'menuCounts' => fn () => $request->user() ? [
                'dosen' => Dosen::count(),
                'project' => Project::count(),
                'matakuliah' => Matakuliah::count(),
            ] : null,
        ];
    }
}
