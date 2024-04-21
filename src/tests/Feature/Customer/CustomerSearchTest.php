<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerSearchTest extends TestCase
{
    /*
     *   Setup will populate five specific customers to search
     */
    public function setUp(): void
    {
        parent::setup();

        $testData = [
            [
                'cust_id' => '1',
                'name' => 'Billy Bobs Vacuum Repair',
            ],
            [
                'cust_id' => '2',
                'name' => 'Acme Anvils',
            ],
            [
                'cust_id' => '3',
                'name' => 'Pinky and The Brains Rehab for Super Villains',
            ],
            [
                'cust_id' => '4',
                'name' => 'Ted Bundy\'s All You Can Eat Buffet',
            ],
            [
                'cust_id' => '5',
                'name' => 'Al\'s Vacuum and Shoes',
            ],
        ];
        foreach ($testData as $data) {
            Customer::factory()->create($data);
        }
    }

    public function test_search_guest()
    {
        $searchData = [
            'basic' => true,
            'perPage' => 25,
        ];

        $response = $this->post(route('customers.search'), $searchData);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_search_all_customers()
    {
        $searchData = [
            'basic' => false,
            'perPage' => 25,
            'searchFor' => null,
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(5, 'data');
    }

    public function test_basic_search_all_customers()
    {
        $searchData = [
            'basic' => true,
            'searchFor' => null,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(5);
    }

    public function test_search_name()
    {
        $searchData = [
            'basic' => false,
            'perPage' => 25,
            'searchFor' => 'vacuum',
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
    }

    public function test_search_name_again()
    {
        $searchData = [
            'basic' => false,
            'perPage' => 25,
            'searchFor' => 'randomStr',
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(0, 'data');
    }

    public function test_search_id()
    {
        $cust = Customer::factory()->create();
        $searchData = [
            'basic' => false,
            'perPage' => 25,
            'searchFor' => strval($cust->cust_id),
            'page' => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data');
    }
}
