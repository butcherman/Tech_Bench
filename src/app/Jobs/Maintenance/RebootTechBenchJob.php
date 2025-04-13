<?php

namespace App\Jobs\Maintenance;

use App\Events\Admin\AdministrationEvent;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Signal a reboot command for all Docker Containers
|-------------------------------------------------------------------------------
*/

class RebootTechBenchJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(DockerControlService $svc): void
    {
        Log::alert('Rebooting Tech Bench');

        event(new AdministrationEvent('Rebooting Tech Bench'));

        $svc->rebootAllContainers();
    }
}
