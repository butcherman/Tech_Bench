<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\WorkbookTableLockEvent;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Jobs\Customer\ValidateWorkbookImportJob;
use App\Jobs\Customer\WorkbookImportTableDataJob;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\User;
use App\Models\WorkbookTableValue;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class WorkbookTableImportTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $response = $this->get(route('cust-workbook.import.index', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful()
            ->assertJsonStructure([[
                'Public Col 1',
                'Public Col 2',
                'Public Col 3',
                'Public Col 4',
            ]]);
    }

    public function test_index_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.import.index', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_index_not_published_as_user(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $response = $this->actingAs($user)
            ->get(route('cust-workbook.import.index', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertSuccessful()
            ->assertJsonStructure([[
                'Public Col 1',
                'Public Col 2',
                'Public Col 3',
                'Public Col 4',
            ]]);
    }

    public function test_index_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.import.index', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        config(['customer.enable_workbooks' => true]);
        Storage::fake('customers');
        Bus::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $form = [
            'file' => $file = UploadedFile::fake()->create('test_import.csv'),
            'publicPage' => 'true',
        ];

        $response = $this->post(
            route('cust-workbook.import.store', [$workbook, $tableIndex]),
            $form
        );

        $response->assertSuccessful()->assertJson(['success' => true]);

        Storage::disk('customers')
            ->assertExists('table_upload/'.$file->hashName());

        $this->assertDatabaseHas('file_uploads', [
            'disk' => 'customers',
            'folder' => 'table_upload',
            'file_name' => 'test_import.csv',
        ]);

        $this->assertTrue(Cache::tags(['data-import'])
            ->has($workbook->wb_hash.$tableIndex));

        Bus::assertDispatched(ValidateWorkbookImportJob::class);
    }

    public function test_store_chunked_file_guest(): void
    {
        config(['customer.enable_workbooks' => true]);
        Storage::fake('customers');
        Bus::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $chunks = [
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '0',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '0',
                'publicPage' => 'true',
                'file' => UploadedFile::fake()->create('test_import.csv'),
            ],
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '1',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '5000000',
                'publicPage' => 'true',
                'file' => UploadedFile::fake()->create('test_import.csv'),
            ],
        ];

        $response = $this->post(
            route('cust-workbook.import.store', [$workbook, $tableIndex]),
            $chunks[0]
        );

        $response->assertSuccessful();
    }

    public function test_store_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $form = [
            'file' => UploadedFile::fake()->create('test_import.csv'),
            'publicPage' => 'true',
        ];

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->post(
                route('cust-workbook.import.store', [$workbook, $tableIndex]),
                $form
            );

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_store_not_published_as_user(): void
    {
        config(['customer.enable_workbooks' => true]);
        Storage::fake('customers');
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $form = [
            'file' => $file = UploadedFile::fake()->create('test_import.csv'),
            'publicPage' => 'true',
        ];

        $response = $this->actingAs($user)
            ->post(
                route('cust-workbook.import.store', [$workbook, $tableIndex]),
                $form
            );

        $response->assertSuccessful()->assertJson(['success' => true]);

        Storage::disk('customers')
            ->assertExists('table_upload/'.$file->hashName());

        $this->assertDatabaseHas('file_uploads', [
            'disk' => 'customers',
            'folder' => 'table_upload',
            'file_name' => 'test_import.csv',
        ]);

        $this->assertTrue(Cache::tags(['data-import'])
            ->has($workbook->wb_hash.$tableIndex));

        Bus::assertDispatched(ValidateWorkbookImportJob::class);
    }

    public function test_store_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $form = [
            'file' => $file = UploadedFile::fake()->create('test_import.csv'),
            'publicPage' => 'true',
        ];

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->post(
                route('cust-workbook.import.store', [$workbook, $tableIndex]),
                $form
            );

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $response = $this->get(route('cust-workbook.import.show', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful();
    }

    public function test_show_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.import.show', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_show_not_published_as_user(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $response = $this->actingAs($user)
            ->get(route('cust-workbook.import.show', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertSuccessful();
    }

    public function test_show_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.import.show', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        config(['customer.enable_workbooks' => true]);
        Event::fake();
        Bus::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $response = $this->put(route('cust-workbook.import.update', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful()->assertJson(['success' => true]);

        Event::assertDispatched(WorkbookTableLockEvent::class);
        Bus::assertDispatched(WorkbookImportTableDataJob::class);
    }

    public function test_update_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->put(route('cust-workbook.import.update', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_update_missing_cache_data(): void
    {
        config(['customer.enable_workbooks' => true]);

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        $response = $this->put(route('cust-workbook.import.update', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful()->assertJson([
            'success' => false,
            'error' => 'File Validation Data Not Found',
        ]);
    }

    public function test_update_not_published_as_user(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $response = $this->actingAs($user)->put(route('cust-workbook.import.update', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful()->assertJson(['success' => true]);
    }

    public function test_update_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        Cache::tags(['data-import'])
            ->put($workbook->wb_hash.$tableIndex, $this->getValidatedData());

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->put(route('cust-workbook.import.update', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $response = $this->delete(route('cust-workbook.import.destroy', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful()->assertJson(['success' => true]);

        $this->assertDatabaseMissing('workbook_table_values', [
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
        ]);
    }

    public function test_destroy_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->delete(route('cust-workbook.import.destroy', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_destroy_not_published_as_user(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $response = $this->actingAs($user)
            ->delete(route('cust-workbook.import.destroy', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertSuccessful()->assertJson(['success' => true]);
    }

    public function test_destroy_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $rowIndex = [Str::uuid(), Str::uuid(), Str::uuid(), Str::uuid()];

        foreach ($rowIndex as $row) {
            for ($i = 1; $i < 5; $i++) {
                WorkbookTableValue::factory()->create([
                    'wb_id' => $workbook->wb_id,
                    'table_index' => $tableIndex,
                    'row_index' => $row,
                    'column_name' => 'Public Col '.$i,
                ]);
            }
        }

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->delete(route('cust-workbook.import.destroy', [
                $workbook,
                $tableIndex,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    public function getValidatedData(): array
    {
        return [
            'data' => [
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
            ],
            'public' => true,
        ];
    }
}
