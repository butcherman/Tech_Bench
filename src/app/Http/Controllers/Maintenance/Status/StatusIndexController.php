<?php

namespace App\Http\Controllers\Maintenance\Status;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\DockerControlService;
use Inertia\Inertia;
use Inertia\Response;

class StatusIndexController extends Controller
{
    public function __construct(protected DockerControlService $svc) {}

    /**
     * Show the current status of the Docker Containers
     */
    public function __invoke(): Response
    {
        $this->authorize('update', AppSettings::class);

        return Inertia::render('Maint/Status/Index', [
            'summary' => fn () => $this->svc->getDockerStatus(),
        ]);
    }
}
