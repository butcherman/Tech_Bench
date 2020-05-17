<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;

class NewCustomerTest extends TestCase
{
    protected $parent;

    //  Populate a parent customer into the database
    public function setUp(): void
    {
        Parent::setup();

        $this->parent = factory(Customers::class)->create();
    }

    //  Verify that a guest cannot visit the page
    public function test_if_guest_can_visit_page()
    {
        $response = $this->get(route('customer.id.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a user that does not have permissions cannot see page
    public function test_user_access_no_permissions()
    {
        $user     = $this->getUserWithoutPermission('Add Customer');
        $response = $this->actingAs($user)->get(route('customer.id.create'));

        $response->assertStatus(403);
    }

    //  Verify that a logged in tech can visit the page
    public function test_add_customer_user_access()
    {
        $user = $this->getTech();

        $response = $this->actingAs($user)->get(route('customer.id.create'));

        $response->assertSuccessful();
        $response->assertViewIs('customer.newCustomer');
    }

    //  Verify that a guest cannot submit a new customer
    public function test_submit_as_guest()
    {
        $custData = factory(Customers::class)->make();

        $data = [
            'cust_id'       => $custData->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        $response = $this->post(route('customer.id.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a user without permissions cannot submit a new customer
    public function test_submit_no_permissions()
    {
        $user     = $this->getUserWithoutPermission('Add Customer');
        $custData = factory(Customers::class)->make();

        $data = [
            'cust_id'       => $custData->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);

        $response->assertStatus(403);
    }

    //  Verify that a tech user can create a new customer
    public function test_submit_new_customer()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();

        $data = [
            'cust_id'       => $custData->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);
    }

    //  Verify that a tech user can create a new customer that is a child of antoher
    public function test_submit_new_child_customer()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();

        $data = [
            'cust_id'       => $custData->cust_id,
            'parent_id'     => $this->parent->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);
    }

    //  Verify that a tech user can create a new customer that is a child of child
    public function test_submit_new_child_customer_with_child_parent()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();
        $child = factory(Customers::class)->create([
            'parent_id' => $this->parent->cust_id,
        ]);

        $data = [
            'cust_id'       => $custData->cust_id,
            'parent_id'     => $child->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);

        //  Verify that the parent id of the new customer is actually the parent and not the other child
        $data['parent_id'] = $this->parent->cust_id;
        unset($data['selectedState']);
        $this->assertDatabaseHas('customers', $data);
    }

    //  Verify that a duplicate customer ID cannot be created
    public function test_duplicate_id()
    {
        $user = $this->getTech();
        $custData = factory(Customers::class)->make();

        $data = [
            'cust_id'       => $custData->cust_id,
            'name'          => $custData->name,
            'dba_name'      => $custData->dba_name,
            'address'       => $custData->address,
            'city'          => $custData->city,
            'selectedState' => $custData->state,
            'zip'           => $custData->zip
        ];

        factory(Customers::class)->create([
            'cust_id' => $custData->cust_id
        ]);

        $response = $this->actingAs($user)->post(route('customer.id.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cust_id']);
    }

    //  Test the "check for duplice id" feature as a guest
    public function test_check_duplicate_customer_response_as_guest()
    {
        $cust = factory(Customers::class)->create();

        $response = $this->get(route('customer.check-id', $cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test the "check for duplice id" feature
    public function test_check_duplicate_customer_response()
    {
        $cust = factory(Customers::class, 5)->create();
        $user = $this->getTech();

        $data = factory(Customers::class)->make();

        $response = $this->actingAs($user)->get(route('customer.check-id', $data->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure(['dup']);
    }

    //  Test the "check for duplice id" feature with a duplicate customer
    public function test_check_duplicate_customer_response_with_duplicate()
    {
        $cust = factory(Customers::class, 5)->create();
        $user = $this->getTech();

        $response = $this->actingAs($user)->get(route('customer.check-id', $cust[1]->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure(['dup', 'name']);
    }
}
