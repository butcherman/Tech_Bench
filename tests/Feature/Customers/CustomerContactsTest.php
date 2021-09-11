<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\UserRolePermissions;
use App\Models\CustomerContactPhone;

class CustomerContactsTest extends TestCase
{
    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'phones'  => [
                'type'      => 'Mobile',
                'number'    => '(213)555-1212',
                'extension' => '232',
            ],
        ];

        $response = $this->post(route('customers.contacts.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_no_permission()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'phones'  => [[
                'type'      => 'Mobile',
                'number'    => '(213)555-1212',
                'extension' => '232',
            ]],
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 14)->update(['allow' => 0]);

        $response = $this->actingAs(User::factory()->create())->post(route('customers.contacts.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'phones'  => [[
                'type'      => 'Mobile',
                'number'    => '(213)555-1212',
                'extension' => '232',
            ]],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.contacts.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'New Contact Created', 'type' => 'success']);
        $this->assertDatabaseHas('customer_contacts', [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number'  => 2135551212,
            'extension'     => 232,
        ]);
    }

    public function test_store_to_parent()
    {
        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $cont = CustomerContact::factory()->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => true,
            'phones'  => [[
                'type'      => 'Mobile',
                'number'    => '(213)555-1212',
                'extension' => '232',
            ]],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.contacts.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'New Contact Created', 'type' => 'success']);
        $this->assertDatabaseHas('customer_contacts', [
            'cust_id' => $cust->parent_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => true,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number'  => 2135551212,
            'extension'     => 232,
        ]);
    }

    /*
    *   Update Method
    */
    public function test_update_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create();
        $mod  = CustomerContact::factory()->make();
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => false,
            'phones'  => [[
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => '(213)555-2121',
                'extension'         => null,
            ]],
        ];

        $response = $this->put(route('customers.contacts.update', $cont->cont_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_no_permission()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create();
        $mod  = CustomerContact::factory()->make();
        $ph   = CustomerContactPhone::factory()->count(2)->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => false,
            'phones'  => [[
                'id'                => $ph[0]->id,
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => $ph[0]->phone_number,
                'extension'         => null,
            ],
            [
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => '(213)555-2121',
                'extension'         => null,
            ]],
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 15)->update(['allow' => 0]);

        $response = $this->actingAs(User::factory()->create())->put(route('customers.contacts.update', $cont->cont_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create();
        $mod  = CustomerContact::factory()->make();
        $ph   = CustomerContactPhone::factory()->count(2)->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => false,
            'phones'  => [[
                'id'                => $ph[0]->id,
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => $ph[0]->phone_number,
                'extension'         => null,
            ],
            [
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => '(213)555-2121',
                'extension'         => null,
            ]],
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.contacts.update', $cont->cont_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Contact Updated', 'type' => 'success']);
        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_id' => $cust->cust_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number'  => 2135552121,
            'extension'     => null,
        ]);
        $this->assertDatabaseMissing('customer_contact_phones', $ph[1]->only('id', 'phone_number', 'phone_type_id', 'extension'));
    }

    public function test_update_to_parent()
    {
        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $cont = CustomerContact::factory()->create();
        $mod  = CustomerContact::factory()->make();
        $ph   = CustomerContactPhone::factory()->count(2)->create(['cont_id' => $cont->cont_id]);
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => true,
            'phones'  => [[
                'id'                => $ph[0]->id,
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => $ph[0]->phone_number,
                'extension'         => null,
            ],
            [
                'phone_number_type' => [ 'description' => 'Mobile'],
                'phone_number'      => '(213)555-2121',
                'extension'         => null,
            ]],
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.contacts.update', $cont->cont_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Contact Updated', 'type' => 'success']);
        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_id' => $cust->parent_id,
            'name'    => $mod->name,
            'email'   => $mod->email,
            'shared'  => true,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number'  => 2135552121,
            'extension'     => null,
        ]);
        $this->assertDatabaseMissing('customer_contact_phones', $ph[1]->only('id', 'phone_number', 'phone_type_id', 'extension'));
    }

    /*
    *   Destroy Method
    */
    public function test_destroy_guest()
    {
        $cont = CustomerContact::factory()->create();

        $response = $this->delete(route('customers.contacts.destroy', $cont->cont_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertDatabaseHas('customer_contacts', $cont->toArray());
    }

    public function test_destroy_no_permission()
    {
        $cont = CustomerContact::factory()->create();

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 16)->update(['allow' => 0]);

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.contacts.destroy', $cont->cont_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $cont = CustomerContact::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.contacts.destroy', $cont->cont_id));
        $response->assertStatus(302);
        $this->assertSoftDeleted('customer_contacts', $cont->toArray());
    }
}
