<?php

namespace App\Http\Controllers\Report;

use App\Actions\BuildReportsMenu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Show Index page for running reports
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Report/Index', [
            'menu' => BuildReportsMenu::getMenu(),
        ]);
    }
}
