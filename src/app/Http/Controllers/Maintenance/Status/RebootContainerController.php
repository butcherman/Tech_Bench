<?php

namespace App\Http\Controllers\Maintenance\Status;

use App\Enums\ContainerList;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Http\Request;

class RebootContainerController extends Controller
{
    public function __construct(protected DockerControlService $svc) {}

    /**
     * Reboot one of the Docker Containers
     */
    public function __invoke(Request $request, ContainerList $container)
    {
        $this->authorize('update', AppSettings::class);

        $this->svc->rebootContainer($container);

        return back()->with('success', 'Service Rebooting');
    }
}
