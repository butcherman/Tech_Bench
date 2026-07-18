<?php

namespace Tests\Feature\Customer;

use App\Exceptions\Customer\WorkbookNotPublishedException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Str;
use Tests\TestCase;

class WorkbookValueTest extends TestCase
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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $response = $this->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertSuccessful()->assertJson(['success' => true]);

        $this->assertDatabaseHas('workbook_values', [
            'wb_id' => $workbook->wb_id,
            'index' => $form['index'],
            'value' => $form['value'],
        ]);
    }

    public function test_invoke_guest_private_value(): void
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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => false,
            'isTable' => false,
        ];

        $response = $this->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertForbidden();

        $this->assertDatabaseMissing('workbook_values', [
            'wb_id' => $workbook->wb_id,
            'index' => $form['index'],
            'value' => $form['value'],
        ]);
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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->put(route('cust-workbook.save-value', $workbook), $form);

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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $response = $this->put(route('cust-workbook.save-value', $workbook), $form);

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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_invoke(): void
    {
        config(['customer.enable_workbooks' => true]);

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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertSuccessful()->assertJson(['success' => true]);

        $this->assertDatabaseHas('workbook_values', [
            'wb_id' => $workbook->wb_id,
            'index' => $form['index'],
            'value' => $form['value'],
        ]);
    }

    public function test_invoke_private_value(): void
    {
        config(['customer.enable_workbooks' => true]);

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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => false,
            'isTable' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertSuccessful()->assertJson(['success' => true]);

        $this->assertDatabaseHas('workbook_values', [
            'wb_id' => $workbook->wb_id,
            'index' => $form['index'],
            'value' => $form['value'],
        ]);
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
            'publish_until' => Carbon::now()->addDays(30),
        ]);
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertNotFound();
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

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
        $form = [
            'value' => 'random val',
            'index' => (string) Str::uuid(),
            'public' => true,
            'isTable' => false,
        ];

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->put(route('cust-workbook.save-value', $workbook), $form);

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }
}
