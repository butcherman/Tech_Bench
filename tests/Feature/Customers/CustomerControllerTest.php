<?php

namespace Tests\Feature\Customers;

use App\Customers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    public function test_index_guest()
    {
        $response = $this->get(route('customer.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('customer.index'));
        $response->assertSuccessful();
        $response->assertViewIs('customers.index');
    }

    public function test_search_guest()
    {
        $searchData = [
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'name'      => 'vaccume'
        ];

        $response = $this->get(route('customer.search', $searchData));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_search()
    {
        $searchData = [
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'name'      => 'vaccume'
        ];

        $response = $this->actingAs($this->getTech())->get(route('customer.search', $searchData));
        $response->assertSuccessful();
        $response->assertJsonStructure([
            "current_page",
            "data",
            "first_page_url",
            "from",
            "last_page",
            "last_page_url",
            "next_page_url",
            "path",
            "per_page",
            "prev_page_url",
            "to",
            "total"]);
    }








    public function test_check_id_guest()
    {
        $cust = factory(Customers::class)->make();
        $response = $this->get(route('customer.check_id', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }


    public function test_check_id_fail()
    {
        $cust = factory(Customers::class)->create();
        $response = $this->actingAs($this->getTech())->get(route('customer.check_id', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => true,
            'name' => $cust->name,
            'url' => route('customer.details', [$cust->cust_id, urlencode($cust->name)]),
        ]);
    }

    public function test_check_id_pass()
    {
        $cust = factory(Customers::class)->make();
        $response = $this->actingAs($this->getTech())->get(route('customer.check_id', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJson([
            'duplicate' => false,
        ]);
    }

    public function test_store_guest()
    {
        $cust = factory(Customers::class)->make();
        $response = $this->post(route('customer.store'), $cust->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $cust = factory(Customers::class)->make();
        $response = $this->actingAs($this->getUserWithoutPermission('Add Customer'))->post(route('customer.store'), $cust->toArray());
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $cust = factory(Customers::class)->make();
        $response = $this->actingAs($this->getUserWithPermission('Add Customer'))->post(route('customer.store'), $cust->toArray());
        $response->assertSuccessful();
        $response->assertJson([
            'success' => true,
            'cust_id' => $cust->cust_id,
        ]);
    }
}
