<?php

namespace App\Http\Controllers\Init;

use App\Actions\Init\BuildApplication;
use App\Exceptions\Maintenance\DockerNotAllowedException;
use App\Http\Controllers\Controller;
use App\Jobs\Maintenance\RebootTechBenchJob;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaveSetupController extends Controller
{
    /**
     * Save the application settings and reboot the Tech Bench.
     */
    public function __invoke(Request $request, BuildApplication $init): JsonResponse
    {
        $init($request->session()->get('setup'));

        // @codeCoverageIgnoreStart
        try {
            new DockerControlService;
            $canReboot = true;
        } catch (DockerNotAllowedException $e) {
            $canReboot = false;
        }
        // @codeCoverageIgnoreEnd

        RebootTechBenchJob::dispatchAfterResponse();

        return response()->json([
            'success' => true,
            'url' => config('app.url'),
            'can_reboot' => $canReboot,
        ]);
    }
}
