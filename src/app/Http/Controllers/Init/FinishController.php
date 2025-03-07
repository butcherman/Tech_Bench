<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class FinishController extends Controller
{
    /**
     * Step 6.  Show the building application screen
     *
     * TODO - Does this need its own controller?
     */
    public function __invoke(): Response
    {
        return Inertia::render('Init/Finish', ['step' => 6]);
    }
}
