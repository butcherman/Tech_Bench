<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FinishController extends Controller
{
    /**
     * Step 6.  Show the building application screen
     */
    public function __invoke()
    {
        return Inertia::render('Init/Finish', ['step' => 6]);
    }
}