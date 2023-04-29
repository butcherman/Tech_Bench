<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
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
                'name' => 'Billy Bobs Vaccume Repair',
                'city' => 'Los Angeles',
            ],
            [
                'cust_id' => '2',
                'name' => 'Acme Anvils',
                'city' => 'Reno',
            ],
            [
                'cust_id' => '3',
                'name' => 'Pinky and The Brains Rehab for Supervillins',
                'city' => 'New York',
            ],
            [
                'cust_id' => '4',
                'name' => 'Ted Bundys All You Can Eat Buffet',
                'city' => 'Bakersfield',
            ],
            [
                'cust_id' => '5',
                'name' => 'Lone Rangers Vaccume Repair',
                'city' => 'New York',
            ],
        ];
        foreach ($testData as $data) {
            Customer::factory()->create($data);
        }
    }

    public function test_search_guest()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
        ];

        $response = $this->post(route('customers.search'), $searchData);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_search_all_customers()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'name' => null,
            'city' => null,
            'equipment' => null,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(5, 'data');
    }

    public function test_search_name()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'name' => 'vaccume',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
    }

    public function test_search_name_again()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'name' => 'randomStr',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(0, 'data');
    }

    public function test_search_city()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'city' => 'New York',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(2, 'data');
    }

    public function test_search_city_again()
    {
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'city' => 'NewYork',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(0, 'data');
    }

    public function test_search_equipment()
    {
        $sys = EquipmentType::factory()->create();
        for ($i = 1; $i < 4; $i++) {
            CustomerEquipment::create([
                'equip_id' => $sys->equip_id,
                'cust_id' => $i,
                'shared' => false,
            ]);
        }

        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'equip' => $sys->name,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
    }

    public function test_search_id()
    {
        $cust = Customer::factory()->create();
        $searchData = [
            'perPage' => 25,
            'sortField' => 'name',
            'sortType' => 'asc',
            'name' => strval($cust->cust_id),
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.search'), $searchData);
        $response->assertSuccessful();
        $response->assertJsonCount(1, 'data');
    }
}
