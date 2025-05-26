<?php

namespace Tests\Unit\Services\TechTip;

use App\Models\FileUpload;
use App\Models\TechTip;
use App\Services\TechTip\TechTipFileService;
use Mockery\MockInterface;
use Tests\TestCase;

class TechTipFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | checkTipFileFolder()
    |---------------------------------------------------------------------------
    */
    public function test_check_tip_file_folder(): void
    {
        $techTip = TechTip::factory()->create();
        $fileList = FileUpload::factory()->count(5)->create(['folder' => 'tmp']);
        $techTip->Files()->sync($fileList->pluck('file_id')->toArray());

        $existingFile = FileUpload::factory()->create(['folder' => $techTip->tip_id]);
        $techTip->Files()->attach($existingFile->file_id);

        /** @var TechTipFileService */
        $testObj = $this->partialMock(
            TechTipFileService::class,
            function (MockInterface $mock) use ($techTip) {
                $mock->shouldReceive('moveUploadedFile')
                    ->times(5)
                    ->with(FileUpload::class, $techTip->tip_id);
            }
        );

        $testObj->checkTipFileFolder($techTip);
    }
}
