<?php

namespace Tests\Unit\Jobs\Customer;

use App\Events\Customer\WorkbookTableImportCompletedEvent;
use App\Jobs\Customer\WorkbookImportTableDataJob;
use App\Models\CustomerEquipmentWorkbook;
use App\Services\Customer\CustomerWorkbookService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class WorkbookImportTableDataJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Event::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        $this->mock(CustomerWorkbookService::class, function (MockInterface $mock) {
            $mock->shouldReceive('importTableData')->once();
        });

        WorkbookImportTableDataJob::dispatch(
            $workbook,
            $tableIndex,
            $this->getValidationResults(),
            1,
            1,
            true
        );

        Event::assertDispatched(WorkbookTableImportCompletedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    protected function getValidationResults(): array
    {
        return [
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
    }
}
