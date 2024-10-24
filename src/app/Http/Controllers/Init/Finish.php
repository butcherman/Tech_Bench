<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Finish extends Controller
{
    use AppSettingsTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return Inertia::render('Init/Finish', ['step' => 6]);
    }
}
