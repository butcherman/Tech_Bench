<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Events\Home\ImageUploadedEvent;
use App\Http\Requests\Home\UploadImageRequest;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    /**
     * Upload an image that will be used in one of the text areas such as a Tech Tip or Customer Note
     */
    public function __invoke(UploadImageRequest $request)
    {
        //  Storage Path
        $path = 'images/uploaded';
        $location = Storage::disk('public')->putFile($path, new File($request->file));

        event(new ImageUploadedEvent($location));
        return ['location' => Storage::url($location)];
    }
}
