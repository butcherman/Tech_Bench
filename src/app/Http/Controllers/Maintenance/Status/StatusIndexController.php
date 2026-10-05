<?php

namespace App\Http\Controllers\Maintenance\Status;

use App\Http\Controllers\Controller;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StatusIndexController extends Controller
{
    public function __construct(protected DockerControlService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) // : Response
    {
        return Inertia::render('Maint/Status/Index', [
            'status' => fn () => $this->svc->getDockerStatus(),
        ]);
    }
}
