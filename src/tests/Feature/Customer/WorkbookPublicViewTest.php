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
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class WorkbookPublicViewTest extends TestCase
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

        $response = $this->get(route('cust-workbook.show', $workbook));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Workbook/Public')
                ->has('customer')
                ->has('workbook')
            );
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

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.show', $workbook));

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
        ]);

        $response = $this->get(route('cust-workbook.show', $workbook));

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
        ]);

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('cust-workbook.show', $workbook));

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

        $response = $this->actingAs($user)
            ->get(route('cust-workbook.show', $workbook));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Workbook/Public')
                ->has('customer')
                ->has('workbook')
            );
    }

    public function test_invoke_not_published(): void
    {
        config(['customer.enable_workbooks' => true]);
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

        $this->expectException(WorkbookNotPublishedException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->get(route('cust-workbook.show', $workbook));

        $response->assertNotFound();

        Exceptions::assertReported(WorkbookNotPublishedException::class);
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

        $response = $this->actingAs($user)
            ->get(route('cust-workbook.show', $workbook));

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
        ]);

        $this->expectException(FeatureDisabledException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->get(route('cust-workbook.show', $workbook));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }
}
