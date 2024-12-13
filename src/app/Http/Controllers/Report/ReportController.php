<?php

namespace App\Http\Controllers\Report;

use App\Actions\Misc\BuildReportsMenu;
use App\Http\Controllers\Controller;
use App\Policies\GatePolicy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Show a list of available reports that can be run.
     */
    public function __invoke(BuildReportsMenu $menu): Response
    {
        $this->authorize('reports-link', GatePolicy::class);

        return Inertia::render('Report/Index', [
            'menu' => $menu(),
        ]);
    }
}
