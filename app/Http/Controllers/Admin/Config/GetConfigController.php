<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GetConfigController extends Controller
{
    /**
     * Application Configuration page
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Admin/App/Config');
    }
}
