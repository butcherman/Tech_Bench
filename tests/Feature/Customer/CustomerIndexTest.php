<?php

namespace Tests\Feature\Customer;

use App\Customers;
use Tests\TestCase;
use App\SystemTypes;
use App\CustomerSystems;

class CustomerIndexTest extends TestCase
{
    public $cust;

    //  Populate some customers in the database
    public function setUp(): void
    {
        Parent::setup();

        $this->cust = factory(Customers::class, 25)->create();
    }

    //  Verify that a guest cannot visit the page
    public function test_visit_customer_index_as_guest()
    {
        $response = $this->get(route('customer.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a logged in tech can visit the page
    public function test_visit_customer_index()
    {
        $user = $this->getTech();

        $response = $this->actingAs($user)->get(route('customer.index'));

        $response->assertSuccessful();
        $response->assertViewIs('customer.index');
    }

    //  Verify that a guest cannot pull a list of customers
    public function test_list_all_customers_as_guest()
    {
        $data = [
            'sortField' => 'name',
            'sortType'  => 'asc',
            'perPage'   => 25,
            'name' => null
        ];

        $response = $this->get(route('customer.search', $data));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a logged in tech can pull a list of customers
    public function test_list_all_customers()
    {
        $user = $this->getTech();
        $data = [
            'sortField' => 'name',
            'sortType'  => 'asc',
            'perPage'   => 25,
            'name' => null
        ];

        $response = $this->actingAs($user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonCount(25, 'data');
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  Search for a specific customer ID and make sure a result is present
    public function test_list_customer_search_id()
    {
        $user = $this->getTech();
        $data = [
            'sortField'   => 'name',
            'sortType'    => 'asc',
            'perPage'     => 25,
            'name'        => $this->cust[1]->cust_id,
            'city'        => null,
            'system_type' => null
        ];

        $response = $this->actingAs($user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  Search for a specific customer name and make sure a result is present
    public function test_list_customer_search_name()
    {
        $user = $this->getTech();
        $data = [
            'sortField'   => 'name',
            'sortType'    => 'asc',
            'perPage'     => 25,
            'name'        => $this->cust[1]->name,
            'city'        => null,
            'system_type' => null
        ];

        $response = $this->actingAs($user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  Search for a specific customer city and make sure a result is present
    public function test_list_customer_search_city()
    {
        $user = $this->getTech();
        $data = [
            'sortField'   => 'name',
            'sortType'    => 'asc',
            'perPage'     => 25,
            'name'        => null,
            'city'        => $this->cust[5]->city,
            'system_type' => null
        ];

        $response = $this->actingAs($user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  Search for a specific system type and return all customers with that system
    public function test_list_customer_search_system()
    {
        $user = $this->getTech();
        $sys  = factory(SystemTypes::class)->create();

        CustomerSystems::create([
            'cust_id' => $this->cust[0]->cust_id,
            'sys_id'  => $sys->sys_id
        ]);

        $data = [
            'sortField'   => 'name',
            'sortType'    => 'asc',
            'perPage'     => 25,
            'name'        => null,
            'city'        => null,
            'system' => $sys->sys_id
        ];

        $response = $this->actingAs($user)->get(route('customer.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(
            [
                'data' => [[
                    'cust_id', 'name', 'dba_name', 'address', 'city', 'state', 'zip'
                ]],
                'meta' => [
                    'current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'
                ]
            ]
        );
    }

    //  TODO:  search for a custome that has been deactivated
}
