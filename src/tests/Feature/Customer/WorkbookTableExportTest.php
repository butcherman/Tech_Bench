<?php

namespace Tests\Feature\Customer;

use App\Actions\Misc\CsvWriter;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTableValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Mockery;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Tests\TestCase;

class WorkbookTableExportTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
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

        $writer = Mockery::mock(SimpleExcelWriter::class);
        $writer->shouldReceive('addRow')->times(4);
        $writer->shouldReceive('toBrowser')->once();

        $csvWriter = Mockery::mock(CsvWriter::class);
        $csvWriter->shouldReceive('stream')
            ->once()
            ->with('data_export.csv')
            ->andReturn($writer);

        $this->app->instance(CsvWriter::class, $csvWriter);

        $response = $this->get(route('cust-workbook.export', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertSuccessful();
    }

    public function test_invoke_guest_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
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

        $response = $this->get(route('cust-workbook.export', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
    }

    public function test_invoke_guest_no_workbook(): void
    {
        config(['customer.enable_workbooks' => true]);

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->make([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';

        $response = $this->get(route('cust-workbook.export', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertNotFound();
    }

    public function test_invoke_guest_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
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

        $response = $this->get(route('cust-workbook.export', [
            $workbook,
            $tableIndex,
        ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }
}
