<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\WorkbookTableRowDeletedEvent;
use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\User;
use App\Models\WorkbookTableValue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Tests\TestCase;

class WorkbookTableDeleteRowTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['customer.enable_workbooks' => true]);
        Event::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowIndex,
        ]);

        $response = $this->delete(route('cust-workbook.del-row', [
            $workbook,
            $tableIndex,
            $rowIndex,
        ]));

        $response->assertSuccessful()->assertJson(['success' => true]);

        Event::assertDispatched(WorkbookTableRowDeletedEvent::class);
    }

    public function test_invoke_guest_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
        Event::fake();
        Exceptions::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowIndex,
        ]);

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->delete(route('cust-workbook.del-row', [
                $workbook,
                $tableIndex,
                $rowIndex,
            ]));

        $response->assertNotFound();

        Event::assertNotDispatched(WorkbookTableRowDeletedEvent::class);
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
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        $response = $this->delete(route('cust-workbook.del-row', [
            $workbook,
            $tableIndex,
            $rowIndex,
        ]));

        $response->assertNotFound();
    }

    public function test_invoke_guest_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Event::fake();
        Exceptions::fake();

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowIndex,
        ]);

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->delete(route('cust-workbook.del-row', [
                $workbook,
                $tableIndex,
                $rowIndex,
            ]));

        $response->assertNotFound();

        Event::assertNotDispatched(WorkbookTableRowDeletedEvent::class);
        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_invoke(): void
    {
        config(['customer.enable_workbooks' => true]);
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowIndex,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('cust-workbook.del-row', [
                $workbook,
                $tableIndex,
                $rowIndex,
            ]));

        $response->assertSuccessful()->assertJson(['success' => true]);

        Event::assertDispatched(WorkbookTableRowDeletedEvent::class);
    }

    public function test_invoke_no_workbook(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->make([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        $response = $this->actingAs($user)
            ->delete(route('cust-workbook.del-row', [
                $workbook,
                $tableIndex,
                $rowIndex,
            ]));

        $response->assertNotFound();
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Event::fake();
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $tableIndex = Str::uuid();
        $rowIndex = Str::uuid();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowIndex,
        ]);

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->delete(route('cust-workbook.del-row', [
                $workbook,
                $tableIndex,
                $rowIndex,
            ]));

        $response->assertNotFound();

        Event::assertNotDispatched(WorkbookTableRowDeletedEvent::class);
        Exceptions::assertReported(FeatureDisabledException::class);
    }
}
