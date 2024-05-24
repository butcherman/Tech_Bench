<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ?string $folderName = null)
    {
        $path = 'images/uploaded/' . $folderName;
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        return ['location' => Storage::url($location)];
    }
}
