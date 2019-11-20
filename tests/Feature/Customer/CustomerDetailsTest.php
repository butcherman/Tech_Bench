<?php

namespace Tests\Feature\Customer;

use App\User;
use App\Customers;
use Tests\TestCase;
use App\UserPermissions;

class CustomerDetailsTest extends TestCase
{
    private $cust;

    //  Populate a customer into the database
    public function setUp(): void
    {
        Parent::setup();

        $this->cust = factory(Customers::class)->create();
    }

    //  Test visit customer details landing page as guest
    public function test_details_page_as_guest()
    {
        $response = $this->get(route('customer.details', [$this->cust->cust_id, $this->cust->name]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test visit customer details landing page as tech
    public function test_details_page()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.details', [$this->cust->cust_id, $this->cust->name]));

        $response->assertSuccessful();
        $response->assertViewIs('customer.details');
    }

    //  Test visit the customer details page with a bad customer ID
    public function test_details_page_bad_customer()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.details', [958745487452, $this->cust->name]));

        $response->assertSuccessful();
        $response->assertViewIs('customer.customerNotFound');
    }

    //  Test marking the customer as a user fav as guest
    public function test_bookmark_customer_as_guest()
    {
        $response = $this->get(route('customer.toggle-fav', ['add', $this->cust->cust_id]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test marking the customer as a user fav
    public function test_bookmark_customer()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.toggle-fav', ['add', $this->cust->cust_id]));

        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);
    }

    //  Test remving the customer as a user fav
    public function test_remove_customer_bookmark()
    {
        $user = $this->getTech();
        $this->actingAs($user)->get(route('customer.toggle-fav', ['add', $this->cust->cust_id]));

        $response = $this->actingAs($user)->get(route('customer.toggle-fav', ['remove', $this->cust->cust_id]));

        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);
    }

    //  Test update customer data as guest
    public function test_update_customer_as_guest()
    {
        $data = [
            'name' => 'New Customer Name',
            'dba_name' => null,
            'address' => '555 Some Drive',
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];

        $response = $this->put(route('customer.id.update', $this->cust->cust_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test update customer as tech
    public function test_update_customer()
    {
        $data = [
            'name' => 'New Customer Name',
            'dba_name' => null,
            'address' => '555 Some Drive',
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);

        $response->assertSuccessful();
        $response->assertJson([
            'success' => true
        ]);
    }

    //  Test trying to nullify the customer name and address
    public function test_update_customer_name_address_validation_error()
    {
        $data = [
            'name' => null,
            'dba_name' => null,
            'address' => null,
            'city' => $this->cust->city,
            'state' => $this->cust->state,
            'zip' => 12345
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'name',
            'address'
        ]);
    }

    //  Test trying to nullify the customer name and address
    public function test_update_customer_city_zip_validation_error()
    {
        $data = [
            'name' => 'New Customer Name',
            'dba_name' => null,
            'address' => '555 Some Address',
            'city' => null,
            'state' => $this->cust->state,
            'zip' => null
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'city',
            'zip'
        ]);
    }

    //  Test deactivating the customer as a guest
    public function test_deactivate_customer_as_guest()
    {
        $response = $this->delete(route('customer.id.destroy', $this->cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test deactivating the customer as a user without permissions
    public function test_deactivate_customer_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 0,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->delete(route('customer.id.destroy', $this->cust->cust_id));

        $response->assertStatus(403);
    }
    //  Test deactivating the customer as a user with permissions
    public function test_deactivate_customer()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 1,
                'run_reports'         => 1,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 1,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 1,
                'create_category'     => 1,
                'modify_category'     => 1
            ]
        );

        $response = $this->actingAs($user)->delete(route('customer.id.destroy', $this->cust->cust_id));

        $response->assertSuccessful();
        $this->assertSoftDeleted('customers', $this->cust->toArray());
    }
}
