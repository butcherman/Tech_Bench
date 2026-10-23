<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class PublishWorkbookTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_equip_id' => $customer->cust_equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'cust_id' => $customer->cust_id,
        ]);
        $form = [
            'publish_until' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->post(route('customers.equipment.workbook.publish', [
            $customer,
            $equipment,
        ]), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_equip_id' => $customer->cust_equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'cust_id' => $customer->cust_id,
        ]);
        $form = [
            'publish_until' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.equipment.workbook.publish', [
                $customer,
                $equipment,
            ]), $form);

        $response->assertStatus(302)
            ->assertSessionHas(['success']);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_equip_id' => $customer->cust_equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'cust_id' => $customer->cust_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $response = $this->delete(route('customers.equipment.workbook.unpublish', [
            $customer,
            $equipment,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $equipment = CustomerEquipment::factory()
            ->create(['cust_equip_id' => $customer->cust_equip_id]);
        CustomerEquipmentWorkbook::factory()->create([
            'cust_equip_id' => $equipment->cust_equip_id,
            'cust_id' => $customer->cust_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $response = $this->actingAs($user)
            ->delete(route('customers.equipment.workbook.unpublish', [
                $customer,
                $equipment,
            ]));

        $response->assertStatus(302)->assertSessionHas('warning');
    }
}
