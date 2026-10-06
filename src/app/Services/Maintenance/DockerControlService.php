<?php

namespace App\Services\Maintenance;

use App\Enums\ContainerList;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

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
     * Reboot a container.
     */
    public function rebootContainer(ContainerList $container)
    {
        $this->http->post('/containers/'.$container->value.'/restart')->throw();
    }
}
