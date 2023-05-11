<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    /**
     * Handle an image uploaded from the Editor Component
     */
    public function __invoke(UploadImageRequest $request)
    {
        //  Storage Path
        $path = 'images/uploaded';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        return ['location' => Storage::url($location)];
    }
}
