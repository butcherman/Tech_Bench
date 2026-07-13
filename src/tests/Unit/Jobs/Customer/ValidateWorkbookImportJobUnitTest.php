<?php

namespace Tests\Unit\Jobs\Customer;

use App\Actions\Customer\ValidateWorkbookImportFile;
use App\Events\Customer\WorkbookTableImportValiationEvent;
use App\Jobs\Customer\ValidateWorkbookImportJob;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class ValidateWorkbookImportJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Storage::fake('customers');
        Event::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $csvFile = UploadedFile::fake()
            ->createWithContent('test_import.csv', $this->getCsvData());
        $dbFile = FileUpload::factory()->create([
            'disk' => 'customers',
            'folder' => 'table_upload',
            'file_name' => 'test_import.csv',
            'hash_name' => 'test_import.csv',
        ]);

        Storage::disk('customers')
            ->putFileAs('table_upload', $csvFile, 'test_import.csv');

        $this->mock(ValidateWorkbookImportFile::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke');
        });

        ValidateWorkbookImportJob::dispatch($workbook, $tableIndex, $dbFile, true);

        Event::assertDispatched(WorkbookTableImportValiationEvent::class);
        $this->assertTrue(Cache::has($workbook->wb_hash.$tableIndex));

    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    protected function getCsvData(bool $withErrors = false)
    {
        $csvData = [
            'Public Col 1,Public Col 2,Public Col 3,Public Col 4',
            'John Doe,Pub 1,false,12345',
            'Jane Doe,Pub 2,1,54321',
        ];

        if ($withErrors) {
            $csvData[0] = 'Public Col 1,Public Col 2,Public Col 3,Invalid Col';
            $csvData[] = 'Billy Bob,bbob@notValid.com,random';
            $csvData[] = 'Too Many,columns,notatall,valid';
        }

        return implode("\n", $csvData);
    }
}
