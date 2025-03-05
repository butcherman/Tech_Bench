<?php

namespace App\Services\Maintenance;

use App\Enums\ContainerList;
use App\Exceptions\Maintenance\DockerNotAllowedException;
use Illuminate\Support\Facades\Process;

class DockerControlService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Check to see if Docker Commands are allowed
        $res = Process::run('docker ps');

        if ($res->failed()) {
            throw new DockerNotAllowedException;
        }
    }

    /**
     * Reboot a single container.
     */
    public function rebootContainer(ContainerList $container): bool
    {
        $status = Process::run('docker restart ' . $container->value);

        return $status->successful();
    }

    /**
     * Reboot all Containers
     */
    public function rebootAllContainers()
    {
        foreach (ContainerList::cases() as $container) {
            $this->rebootContainer($container);
        }
    }
}
