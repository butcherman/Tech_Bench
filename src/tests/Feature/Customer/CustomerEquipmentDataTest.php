<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerEquipmentDataFieldChanged;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerEquipmentDataTest extends TestCase
{
    protected $customer;

    protected $equipment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->create();
        $this->equipment = CustomerEquipment::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);

    }

    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $data = [
            'saveData' => [
                'fieldId' => 1,
                'value' => '12.52.25.1',
            ],
        ];

        $response = $this->put(
            route('customers.update-equipment-data', $this->customer->slug),
            $data
        );
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_one_field(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $fields = DataField::factory()
            ->count(4)
            ->create(['equip_id' => $this->equipment->equip_id]);

        $data1 = CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => '10.1.25.12',
        ]);
        CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1 = '3.5',
        ]);
        CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2 = 'admin',
        ]);
        CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $value3 = 'password',
        ]);

        $data = [
            'saveData' => [
                [
                    'fieldId' => $data1->id,
                    'value' => $newVal0 = '12.52.25.1',
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(
                route('customers.update-equipment-data', $this->customer->slug),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Saved Successfully');

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => $newVal0,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $value3,
        ]);

        Event::assertDispatched(CustomerEquipmentDataFieldChanged::class);
    }

    public function test_invoke_all_fields(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $fields = DataField::factory()
            ->count(4)
            ->create(['equip_id' => $this->equipment->equip_id]);

        $data1 = CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => '10.1.25.12',
        ]);
        $data2 = CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $value1 = '3.5',
        ]);
        $data3 = CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $value2 = 'admin',
        ]);
        $data4 = CustomerEquipmentData::create([
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $value3 = 'password',
        ]);

        $data = [
            'saveData' => [
                [
                    'fieldId' => $data1->id,
                    'value' => $newVal0 = '12.52.25.1',
                ],
                [
                    'fieldId' => $data2->id,
                    'value' => $newVal1 = '8.6',
                ],
                [
                    'fieldId' => $data3->id,
                    'value' => $newVal2 = 'billy_bob',
                ],
                [
                    'fieldId' => $data4->id,
                    'value' => $newVal3 = 'newPassword',
                ],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(
                route('customers.update-equipment-data', $this->customer->slug),
                $data
            );
        $response->assertStatus(302)
            ->assertSessionHas('success', 'Saved Successfully');

        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[0]->field_id,
            'value' => $newVal0,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[1]->field_id,
            'value' => $newVal1,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[2]->field_id,
            'value' => $newVal2,
        ]);
        $this->assertDatabaseHas('customer_equipment_data', [
            'cust_equip_id' => $this->equipment->cust_equip_id,
            'field_id' => $fields[3]->field_id,
            'value' => $newVal3,
        ]);

        Event::assertDispatched(CustomerEquipmentDataFieldChanged::class);
    }
}
