<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Finish extends Controller
{
    use AppSettingsTrait;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $this->clearSetting('app.first_time_setup');

        return Inertia::render('Init/Finish', ['step' => 5]);
    }
}
