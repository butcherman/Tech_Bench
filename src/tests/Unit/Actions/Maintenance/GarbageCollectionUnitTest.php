<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\GarbageCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GarbageCollectionUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Storage::fake();

        // Populate the chunks folder
        for ($i = 0; $i < 5; $i++) {
            Storage::putFile(
                'chunks',
                UploadedFile::fake()->image($i.'.png')
            );
        }

        $testObj = new GarbageCollection;
        $testObj();

        Storage::assertDirectoryEmpty('chunks');

        $this->assertDatabaseCount('failed_jobs', 0);
    }
}
