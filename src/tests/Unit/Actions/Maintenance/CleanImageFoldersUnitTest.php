<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\CleanImageFolders;
use App\Models\TechTip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CleanImageFoldersUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_fix_off(): void
    {
        Storage::fake('public');
        TechTip::factory()->create([
            'details' => '<img src="/storage/images/uploaded/test_file_0.png" />',
        ]);

        // Generate some fake files
        for ($i = 0; $i < 10; $i++) {
            Storage::disk('public')
                ->putFileAs(
                    'images/logo',
                    UploadedFile::fake()->image('test_file_'.$i.'.png'),
                    'test_file_'.$i.'.png'
                );
            Storage::disk('public')
                ->putFileAs(
                    'images/uploaded',
                    UploadedFile::fake()->image('test_file_'.$i.'.png'),
                    'test_file_'.$i.'.png'
                );
        }

        config(['app.logo' => '/storage/images/logo/test_file_0.png']);

        $testObj = new CleanImageFolders;
        $res = $testObj(false);

        $this->assertEquals(18, $res);

        Storage::disk('public')->assertExists('images/logo/test_file_9.png');
        Storage::disk('public')->assertExists('images/uploaded/test_file_9.png');
    }

    public function test_invoke_fix_on(): void
    {
        Storage::fake('public');
        TechTip::factory()->create([
            'details' => '<img src="/storage/images/uploaded/test_file_0.png" />',
        ]);

        // Generate some fake files
        for ($i = 0; $i < 10; $i++) {
            Storage::disk('public')
                ->putFileAs(
                    'images/logo',
                    UploadedFile::fake()->image('test_file_'.$i.'.png'),
                    'test_file_'.$i.'.png'
                );
            Storage::disk('public')
                ->putFileAs(
                    'images/uploaded',
                    UploadedFile::fake()->image('test_file_'.$i.'.png'),
                    'test_file_'.$i.'.png'
                );
        }

        config(['app.logo' => '/storage/images/logo/test_file_0.png']);

        $testObj = new CleanImageFolders;
        $res = $testObj(true);

        $this->assertEquals(18, $res);

        Storage::disk('public')->assertExists('images/logo/test_file_0.png');
        Storage::disk('public')->assertExists('images/uploaded/test_file_0.png');
        Storage::disk('public')->assertMissing('images/logo/test_file_9.png');
        Storage::disk('public')->assertMissing('images/uploaded/test_file_9.png');
    }
}
