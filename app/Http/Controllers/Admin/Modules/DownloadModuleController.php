<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadModuleController extends Controller
{
    /**
     * Download and Stage a module for installation
     */
    // public function __invoke(Request $request)
    // {
    //     $url = $request->download_link;

    //     $response = Http::get($url);
    //     Storage::disk('modules')->put($request->alias.'.zip', $response);

    //     return 'downloaded';
    // }
}
