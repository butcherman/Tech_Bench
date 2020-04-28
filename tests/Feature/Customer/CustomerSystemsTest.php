<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use App\SystemTypes;
use App\CustomerSystems;
use App\SystemDataFields;
use App\CustomerSystemData;

class CustomerSystemsTest extends TestCase
{
    private $cust, $user, $system, $sysData;

    public function setUp(): void{
        Parent::setup();

        //  Setup a default system to test with
        $this->user = $this->getTech();
        $this->cust = factory(Customers::class)->create();
        $this->system = factory(CustomerSystems::class)->create([
            'cust_id' => $this->cust->cust_id
        ]);
        $this->sysData = factory(CustomerSystemData::class, 5)->create([
            'cust_sys_id' => $this->system->cust_sys_id
        ]);
    }

    //  Try to get a customers systems as a guest
    public function test_get_customer_systems_as_guest()
    {
        $response = $this->get(route('customer.systems.show', $this->cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to get a customers systems as a logged in user
    public function test_get_customer_systems()
    {
        $response = $this->actingAs($this->user)->get(route('customer.systems.show', $this->cust->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
            'cust_sys_id',
            'cust_id',
            'sys_id',
            'shared',
            'sys_name',
            'customer_system_data'
        ]]);
    }

    //  Try to get a customers systems from the parent
    public function test_get_customer_systems_from_parent()
    {
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        factory(CustomerSystems::class)->create([
            'cust_id' => $this->cust->cust_id,
            'shared'  => 1,
        ]);
        $response = $this->actingAs($this->user)->get(route('customer.systems.show', $child->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
            'cust_sys_id',
            'cust_id',
            'sys_id',
            'shared',
            'sys_name',
            'customer_system_data'
        ]]);
    }

    //  Try to get a customers system for a customer that does not exist
    public function test_get_customer_systems_bad_id()
    {
        $response = $this->actingAs($this->user)->get(route('customer.systems.show', 243452342345));

        $response->assertStatus(404);
    }

    //  Test trying to get a list of available systems as a guest
    public function test_get_system_list_as_guest()
    {
        $response = $this->get(route('customer.systems.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test trying to get a list of available systems
    public function test_get_system_list()
    {
        $response = $this->actingAs($this->user)->get(route('customer.systems.index'));

        $response->assertSuccessful();
        $response->assertJsonStructure([[
            'cat_id',
            'name',
        ]]);
    }

    //  Try to add a system to a customer as a guest
    public function test_add_system_as_guest()
    {
        $system = factory(SystemTypes::class)->create();
        $fields = factory(SystemDataFields::class, 3)->create();
        $data = [
            'cust_id' => $this->cust->cust_id,
            'equip'  => [
                'name' => $system->name,
                'sys_id' => $system->sys_id,
            ],
            'share'  => false,
        ];
        foreach($fields as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->post(route('customer.systems.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to add a system to a customer
    public function test_add_system()
    {
        $system = factory(SystemTypes::class)->create();
        $fields = factory(SystemDataFields::class, 3)->create();
        $data = [
            'cust_id' => $this->cust->cust_id,
            'equip'  => [
                'name' => $system->name,
                'sys_id' => $system->sys_id,
            ],
            'share'  => false,
        ];
        foreach($fields as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->actingAs($this->user)->post(route('customer.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Try to add a system to a customer parent
    public function test_add_system_link_to_parent()
    {
        $child  = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $system = factory(SystemTypes::class)->create();
        $fields = factory(SystemDataFields::class, 3)->create();
        $data = [
            'cust_id' => $child->cust_id,
            'equip'  => [
                'name' => $system->name,
                'sys_id' => $system->sys_id,
            ],
            'share'  => true,
        ];
        foreach($fields as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->actingAs($this->user)->post(route('customer.systems.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test updating the system data as a guest
    public function test_update_system_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'equip'  => [
                'name' => $this->system->name,
                'sys_id' => $this->system->sys_id,
            ],
            'share'  => false,
        ];
        foreach($this->sysData as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->put(route('customer.systems.update', $this->system->cust_sys_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test updating the system data
    public function test_update_system()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'equip'  => [
                'name' => 'this is a name',
                'sys_id' => $this->system->sys_id,
            ],
            'share'  => false,
        ];
        foreach($this->sysData as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->actingAs($this->user)->put(route('customer.systems.update', $this->system->cust_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test updating the system data with mismatching data
    public function test_update_system_mismatch_data()
    {
        $anotherCust = factory(Customers::class)->create();
        $data = [
            'cust_id' => $anotherCust->cust_id,
            'equip'  => [
                'name' => 'this is a name',
                'sys_id' => $this->system->sys_id,
            ],
            'share'  => false,
        ];
        foreach($this->sysData as $field)
        {
            $data['fields'][] = [
                'field_id' => $field->field_id,
                'value' => 'Some amazing value',
            ];
        }

        $response = $this->actingAs($this->user)->put(route('customer.systems.update', $anotherCust->cust_id), $data);

        $response->assertStatus(404);
    }

    //  Test deleting a system as a guest
    public function test_delete_system_as_guest()
    {
        $response = $this->delete(route('customer.delEquip', [$this->system->cust_sys_id, $this->cust->cust_id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test deleting a system
    public function test_delete_system()
    {
        $response = $this->actingAs($this->user)->delete(route('customer.delEquip', [$this->system->cust_sys_id, $this->cust->cust_id]));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
