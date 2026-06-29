<?php

namespace Tests\Feature\Customer;

use App\Exceptions\Customer\EquipmentWorkbookNotFoundException;
use App\Exceptions\Misc\FeatureDisabledException;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\EquipmentWorkbook;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerEquipmentWorkbookTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $response = $this->get(route('customers.equipment.workbook.index', [
            $customer,
            $equipment,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.workbook.index', [
                $customer,
                $equipment,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_index_missing_workbook(): void
    {
        config(['customer.enable_workbooks' => true]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.workbook.index', [
                $customer,
                $equipment,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(EquipmentWorkbookNotFoundException::class);
    }

    public function test_index(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.workbook.index', [
                $customer,
                $equipment,
            ]));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Customer/Workbook/Index')
                ->has('customer')
                ->has('equipment')
                ->has('workbook')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);

        $response = $this->get(route('customers.equipment.workbook.create', [
            $customer,
            $equipment,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.workbook.create', [
                $customer,
                $equipment,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_create(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);

        $response = $this->actingAs($user)
            ->get(route('customers.equipment.workbook.create', [
                $customer,
                $equipment,
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('customers.equipment.workbook.index', [
                $customer,
                $equipment,
            ]));

        $this->assertDatabaseHas('customer_equipment_workbooks', [
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $response = $this->put(route('customers.equipment.workbook.update', [
            $customer,
            $equipment,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.workbook.update', [
                $customer,
                $equipment,
            ]));

        $response->assertNotFound();

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_update(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_id' => $customer->cust_id]);
        EquipmentWorkbook::factory()
            ->create(['equip_id' => $equipment->equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
        ]);

        $response = $this->actingAs($user)
            ->put(route('customers.equipment.workbook.update', [
                $customer,
                $equipment,
            ]));

        $response->assertStatus(302)
            ->assertRedirect(route('customers.equipment.workbook.index', [
                $customer,
                $equipment,
            ]))->assertSessionHas('success');
    }
}
