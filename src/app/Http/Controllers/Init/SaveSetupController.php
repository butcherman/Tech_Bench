<?php

namespace App\Http\Controllers\Init;

use App\Actions\Init\BuildApplication;
use App\Exceptions\Maintenance\DockerNotAllowedException;
use App\Http\Controllers\Controller;
use App\Jobs\Maintenance\RebootTechBenchJob;
use App\Services\Maintenance\DockerControlService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveSetupController extends Controller
{
    /**
     * Save the current Init step in the session and move onto the next step.
     */
    public function __invoke(Request $request, BuildApplication $init)
    {
        $init($request->session()->get('setup'));

        try {
            new DockerControlService;
            $canReboot = true;
        } catch (DockerNotAllowedException $e) {
            $canReboot = false;
        }

        RebootTechBenchJob::dispatchAfterResponse();

        return response()->json([
            'success' => true,
            'url' => config('app.url'),
            'can_reboot' => $canReboot,
        ]);
    }
}
