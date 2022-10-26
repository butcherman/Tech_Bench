<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Http\Controllers\Controller;
use App\Traits\LogUtilitiesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class LogsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * System Logs landing page
     */
    public function __invoke()
    {
        return Inertia::render('Admin/Logs/Index', [
            'channels' => Arr::pluck($this->logChannels, 'name'),
        ]);
    }
}
