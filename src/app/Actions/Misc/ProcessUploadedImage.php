<?php

namespace App\Actions\Misc;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedImage
{
    /**
     * Base path of uploaded images
     *
     * @var string
     */
    protected $basePath = 'images/uploaded/';

    /*
    |---------------------------------------------------------------------------
    | Take an image that was uploaded via TinyMCE Editor and save it to a
    | folder that can be accessed publicly.
    |---------------------------------------------------------------------------
    */
    public function __invoke(UploadedFile $file, ?string $folder = null): string
    {
        if ($folder) {
            $this->basePath .= $folder;
        }

        $storageLocation = Storage::disk('public')
            ->putFile($this->basePath, new File($file));

        return Storage::url($storageLocation);
    }
}
