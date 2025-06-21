<?php

namespace App\Exceptions\Maintenance;

use Exception;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Exception is triggered if a Docker command fails from a docker container.
| This feature can be removed by users in the docker-compose file.
|-------------------------------------------------------------------------------
*/

class DockerNotAllowedException extends Exception
{
    public function report(Exception $e): void
    {
        Log::alert('Docker Commands are not allowed from Tech Bench containers.  Consult Documentation on how to properly set permissions to allow Docker Commands');
    }

    public function render()
    {
        abort(500, 'Docker Commands are not allowed from Tech Bench Containers.');
    }
}
