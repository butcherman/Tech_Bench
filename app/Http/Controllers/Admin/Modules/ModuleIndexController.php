<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nwidart\Modules\Facades\Module;

class ModuleIndexController extends Controller
{
    /**
     * Allow Administrators to add and remove Modules
     */
    // public function __invoke(Request $request)
    // {
    //     $this->authorize('viewAny', AppSettings::class);

    //     $moduleList = Module::all();


    //     return Inertia::render('Admin/Modules/Index');
    // }
}
