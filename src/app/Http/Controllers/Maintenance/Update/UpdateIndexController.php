<?php

namespace App\Http\Controllers\Maintenance\Update;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Update\GitHubReleaseService;
use Inertia\Inertia;
use Inertia\Response;

class UpdateIndexController extends Controller
{
    public function __construct(
        protected GitHubReleaseService $svc
    ) {}

    /**
     * Display available Tech Bench application updates.
     */
    public function __invoke(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/Updates', [
            'release' => $this->svc->check(),
        ]);
    }
}
