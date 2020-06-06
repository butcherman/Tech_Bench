<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Domains\FilesDomain;

class FilesDomainTest extends TestCase
{
    public function test_save_file()
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('testImage.png');
        $obj = new FilesDomain();

        //  Upload the file the first time to verify it works
        $res = $obj->saveFile($file);
        $this->assertEquals($res, 'testImage.png');

        //  Upload the file the second time to verify it will be saved with a different name
        $res2 = $obj->saveFile($file);
        $this->assertEquals($res2, 'testImage(1).png');

        //  Upload the file a third time to verify the appending number is incremented
        $res3 = $obj->saveFile($file);
        $this->assertEquals($res3, 'testImage(2).png');

        //  Verify all files are in place
        Storage::disk('local')->assertExists('default\testImage.png');
        Storage::disk('local')->assertExists('default\testImage(1).png');
        Storage::disk('local')->assertExists('default\testImage(2).png');
    }
}
