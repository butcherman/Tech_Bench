<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\UploadImageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UploadImageController extends Controller
{
    /**
     * Handle an image upload from the Vue Editor Component
     */
    public function __invoke(UploadImageRequest $request, ?string $folderName = null): array
    {
        $path = 'images/uploaded/' . $folderName;
        $location = Storage::disk('public')
            ->putFile($path, new File($request->file));

        return ['location' => Storage::url($location)];
    }
}
