<?php

namespace App\Services\Maintenance;

use App\Enums\ContainerList;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

/**
 * @codeCoverageIgnore
 */
class DockerControlService
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::baseUrl(config('services.docker_manager.url'))
            ->withToken(config('services.docker_manager.api_key'));
    }

    /**
     * Get the status of all Docker Containers
     */
    public function getDockerStatus(): array
    {
        return $this->http->get('/containers')->throw()->json('containers');
    }

    /**
     * Reboot a single container.
     */
    // public function rebootContainer(ContainerList $container): bool
    // {
    //     // In Testing Environment, we do not want to trigger reboot
    //     if (App::environment('testing')) {
    //         return true;
    //     }

    //     $status = Process::run('docker restart '.$container->value);

    //     return $status->successful();
    // }

    /**
     * Reboot all Containers
     */
    // public function rebootAllContainers(): void
    // {
    //     // In Testing Environment, we do not want to trigger reboot
    //     if (App::environment('testing')) {
    //         return;
    //     }

    //     foreach (ContainerList::cases() as $container) {
    //         $this->rebootContainer($container);
    //     }
    // }
}
