<?php

namespace App\Services\Maintenance;

use App\Enums\ContainerList;
use App\Exceptions\Maintenance\DockerNotAllowedException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Process;

/**
 * @codeCoverageIgnore
 */
class DockerControlService
{
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
        // In Testing Environment, we do not want to trigger reboot
        if (App::environment('testing')) {
            return true;
        }

        $status = Process::run('docker restart '.$container->value);

        return $status->successful();
    }

    /**
     * Reboot all Containers
     */
    public function rebootAllContainers(): void
    {
        // In Testing Environment, we do not want to trigger reboot
        if (App::environment('testing')) {
            return;
        }

        foreach (ContainerList::cases() as $container) {
            $this->rebootContainer($container);
        }
    }
}
