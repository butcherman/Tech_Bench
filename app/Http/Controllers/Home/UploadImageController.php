<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UploadImageRequest $request)
    {
        $path = 'images/uploaded';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        return ['location' => Storage::url($location)];
    }
}
