<?php

namespace Tests\Unit\Actions\Customer;

use App\Actions\Customer\ValidateWorkbookImportFile;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidateWorkbookImportFileUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_no_errors(): void
    {
        Storage::fake('customers');

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

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $table = '4e2eae40-b892-4509-818a-b03191dbc237';

        $obj = new ValidateWorkbookImportFile;
        $res = $obj($workbook, $table, $dbFile);

        // Testing Results
        $shouldBe = [
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'John Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 1',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => false,
                    'validation_error' => null,
                ],
                'Public Col 4' => [
                    'valid' => true,
                    'data_type' => 'integer',
                    'value' => 12345,
                    'validation_error' => null,
                ],
            ],
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'Jane Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 2',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => true,
                    'validation_error' => null,
                ],
                'Public Col 4' => [
                    'valid' => true,
                    'data_type' => 'integer',
                    'value' => 54321,
                    'validation_error' => null,
                ],
            ],
        ];

        $this->assertEquals($res->toArray(), $shouldBe);
    }

    public function test_invoke_with_errors(): void
    {
        Storage::fake('customers');

        $csvFile = UploadedFile::fake()
            ->createWithContent('test_import.csv', $this->getCsvData(true));
        $dbFile = FileUpload::factory()->create([
            'disk' => 'customers',
            'folder' => 'table_upload',
            'file_name' => 'test_import.csv',
            'hash_name' => 'test_import.csv',
        ]);
        Storage::disk('customers')
            ->putFileAs('table_upload', $csvFile, 'test_import.csv');

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $table = '4e2eae40-b892-4509-818a-b03191dbc237';

        $obj = new ValidateWorkbookImportFile;
        $res = $obj($workbook, $table, $dbFile);

        // Testing Results
        $shouldBe = [
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'John Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 1',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => false,
                    'validation_error' => null,
                ],
                'Invalid Col' => [
                    'valid' => false,
                    'data_type' => null,
                    'value' => '12345',
                    'validation_error' => 'Invalid Column',
                ],

            ],
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'Jane Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 2',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => true,
                    'validation_error' => null,
                ],
                'Invalid Col' => [
                    'valid' => false,
                    'data_type' => null,
                    'value' => '54321',
                    'validation_error' => 'Invalid Column',
                ],

            ],
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'Billy Bob',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => false,
                    'data_type' => 'enum',
                    'value' => 'bbob@notValid.com',
                    'validation_error' => 'enum expected',
                ],
                'Public Col 3' => [
                    'valid' => false,
                    'data_type' => 'boolean',
                    'value' => null,
                    'validation_error' => 'boolean expected',
                ],
                'Invalid Col' => [
                    'valid' => false,
                    'data_type' => null,
                    'value' => '',
                    'validation_error' => 'Invalid Column',
                ],
            ],
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'Too Many',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => false,
                    'data_type' => 'enum',
                    'value' => 'columns',
                    'validation_error' => 'enum expected',
                ],
                'Public Col 3' => [
                    'valid' => false,
                    'data_type' => 'boolean',
                    'value' => null,
                    'validation_error' => 'boolean expected',
                ],
                'Invalid Col' => [
                    'valid' => false,
                    'data_type' => null,
                    'value' => null,
                    'validation_error' => 'Invalid Column',
                ],
            ],
        ];

        $this->assertEquals($res->toArray(), $shouldBe);
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
            $csvData[] = 'Too Many,columns,notatall,';
        }

        return implode("\n", $csvData);
    }
}
