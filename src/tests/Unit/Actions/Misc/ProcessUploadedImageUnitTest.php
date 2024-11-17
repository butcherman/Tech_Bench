<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\ProcessUploadedImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessUploadedImageUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_no_folder()
    {
        Storage::fake('public');

        $fileData = UploadedFile::fake()->image('testPhoto.png');

        $testObj = new ProcessUploadedImage;
        $res = $testObj($fileData);

        $resData = explode('/', $res);

        Storage::disk('public')->assertExists('images/uploaded/'.end($resData));
    }

    public function test_invoke_with_folder()
    {
        Storage::fake('public');

        $fileData = UploadedFile::fake()->image('testPhoto.png');
        $folder = 'uploadTest';

        $testObj = new ProcessUploadedImage;
        $res = $testObj($fileData, $folder);

        $resData = explode('/', $res);

        Storage::disk('public')
            ->assertExists('images/uploaded/'.$folder.'/'.end($resData));
    }
}
