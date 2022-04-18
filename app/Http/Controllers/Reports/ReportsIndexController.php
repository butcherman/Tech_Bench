<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportsIndexController extends Controller
{
    /**
     * Landing page for system reports
     */
    public function __invoke()
    {
        return Inertia::render('Reports/Index');
    }
}
