<?php

namespace Tests\Unit\Actions\File;

use App\Actions\File\CleanUploadedImages;
use App\Models\CustomerNote;
use App\Models\TechTip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanUploadedImagesUnitTest extends TestCase
{
    /** @var array */
    protected $fileList = [];

    /** @var string */
    protected $logo;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create five logo files.
        for ($i = 0; $i < 5; $i++) {
            $this->fileList[] = Storage::disk('public')
                ->putFile(
                    'images/logo',
                    UploadedFile::fake()->image($i.'.png')
                );
        }

        // Create 10 abandoned uploaded file images
        for ($i = 0; $i < 5; $i++) {
            $this->fileList[] = Storage::disk('public')
                ->putFile(
                    'images/uploaded',
                    UploadedFile::fake()->image($i.'.png')
                );
        }

        // Create a used logo file
        $this->logo = Storage::disk('public')
            ->putFile('images/logo', UploadedFile::fake()->image('logo.png'));
        config(['app.logo' => $this->logo]);

        // Create a used Tech Tip image file
        $tipImage = Storage::disk('public')
            ->putFile(
                'images/uploaded',
                UploadedFile::fake()->image('tip.png')
            );
        TechTip::factory()->create([
            'details' => '<img src='.$tipImage.' />',
        ]);

        // Create a used Customer Note file
        $custNote = Storage::disk('public')
            ->putFile(
                'images/uploaded',
                UploadedFile::fake()->image('note.png')
            );
        CustomerNote::factory()->create([
            'details' => '<img src='.$custNote.' />',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_fix_off(): void
    {
        $testObj = new CleanUploadedImages;

        $res = $testObj(false);

        $this->assertEquals($res, [
            'logo_files' => 5,
            'upload_files' => 5,
            'total_files' => 10,
            'deleted' => false,
        ]);

        // Verify files were not deleted
        foreach ($this->fileList as $file) {
            Storage::disk('public')->assertExists($file);
        }
    }

    public function test_invoke_fix_on(): void
    {
        $testObj = new CleanUploadedImages;

        $res = $testObj(true);

        $this->assertEquals($res, [
            'logo_files' => 5,
            'upload_files' => 5,
            'total_files' => 10,
            'deleted' => true,
        ]);

        // Verify files were not deleted
        foreach ($this->fileList as $file) {
            Storage::disk('public')->assertMissing($file);
        }
    }
}
