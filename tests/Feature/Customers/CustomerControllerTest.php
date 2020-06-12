<?php

namespace Tests\Feature\Customers;

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
}
