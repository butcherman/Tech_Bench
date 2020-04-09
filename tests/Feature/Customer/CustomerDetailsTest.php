<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;

class CustomerDetailsTest extends TestCase
{
    private $cust, $childCust;

    //  Populate a customer into the database
    public function setUp(): void
    {
        Parent::setup();

        $this->cust = factory(Customers::class)->create();
        $this->childCust = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
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
        $user     = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.details', [$this->cust->cust_id, $this->cust->name]));

        $response->assertSuccessful();
        $response->assertViewIs('customer.details');
    }

    //  Test visit the customer details page with a bad customer ID
    public function test_details_page_bad_customer()
    {
        $user     = $this->getTech();
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
        $user     = $this->getTech();
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
            'name'     => 'New Customer Name',
            'dba_name' => null,
            'address'  => '555 Some Drive',
            'city'     => $this->cust->city,
            'state'    => $this->cust->state,
            'zip'      => 12345
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
            'name'     => 'New Customer Name',
            'dba_name' => null,
            'address'  => '555 Some Drive',
            'city'     => $this->cust->city,
            'state'    => $this->cust->state,
            'zip'      => 12345
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
            'name'     => null,
            'dba_name' => null,
            'address'  => null,
            'city'     => $this->cust->city,
            'state'    => $this->cust->state,
            'zip'      => 12345
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
            'name'     => 'New Customer Name',
            'dba_name' => null,
            'address'  => '555 Some Address',
            'city'     => null,
            'state'    => $this->cust->state,
            'zip'      => null
        ];
        $user = $this->getTech();
        $response = $this->actingAs($user)->put(route('customer.id.update', $this->cust->cust_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'city',
            'zip'
        ]);
    }

    //  Test linking a customer to a parent
    public function test_link_to_parent()
    {
        $user = $this->getTech();
        $parent = factory(Customers::class)->create();

        $data = [
            'parent_id' => $parent->cust_id,
            'cust_id'   => $this->cust->cust_id,
        ];

        $response = $this->actingAs($user)->post(route('customer.linkParent'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test linking a customer to a parent when assigning another child as parent
    public function test_link_to_parent_via_child()
    {
        $user = $this->getTech();
        $parent = factory(Customers::class)->create();
        $child  = factory(Customers::class)->create([
            'parent_id' => $parent->cust_id,
        ]);

        $data = [
            'parent_id' => $child->cust_id,
            'cust_id'   => $this->cust->cust_id,
        ];

        $response = $this->actingAs($user)->post(route('customer.linkParent'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test removing a linked customer
    public function test_remove_parent_link()
    {
        $user = $this->getTech();
        $linkedParent = factory(Customers::class)->create();
        $child = factory(Customers::class)->create([
            'parent_id' => $linkedParent->cust_id,
        ]);

        $response = $this->actingAs($user)->get(route('customer.removeParent', $child->cust_id));

        $response->assertSuccessful();
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
        $user     = $this->userWithoutPermission('Deactivate Customer');
        $response = $this->actingAs($user)->delete(route('customer.id.destroy', $this->cust->cust_id));

        $response->assertStatus(403);
    }
    //  Test deactivating the customer as a user with permissions
    public function test_deactivate_customer()
    {
        $user     = $this->getInstaller();
        $response = $this->actingAs($user)->delete(route('customer.id.destroy', $this->cust->cust_id));

        $response->assertSuccessful();
        //  TODO  - Fix this to verify the customer has been removed
        //  Remove the "Child Count" field to test the customer has been deactivated
        // $this->cust->forget('child_count')->all();
        // $this->assertSoftDeleted('customers', $this->cust->toArray());
    }
}
