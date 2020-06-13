<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\CustomerSystems;
use App\Domains\Customers\CustomerSearch;
use App\Http\Requests\Customers\CustomerSearchRequest;
use App\SystemTypes;
use Tests\TestCase;

class CustomerSearchTest extends TestCase
{
    protected $testObj;

    //  Populate specific customer data to perform search functions on
    public function setUp(): void
    {
        Parent::setup();

        $testData = [
            [
                'cust_id' => '1',
                'name'    => 'Billy Bobs Vaccume Repair',
                'city'    => 'Los Angeles',
            ],
            [
                'cust_id' => '2',
                'name'    => 'Acme Anvils',
                'city'    => 'Reno',
            ],
            [
                'cust_id' => '3',
                'name'    => 'Pinky and The Brains Rehab for Supervillins',
                'city'    => 'New York',
            ],
            [
                'cust_id' => '4',
                'name'    => 'Ted Bundys All You Can Eat Buffet',
                'city'    => 'Bakersfield',
            ],
            [
                'cust_id' => '5',
                'name'    => 'Lone Rangers Vaccume Repair',
                'city'    => 'New York',
            ],
        ];
        foreach($testData as $data)
        {
            factory(Customers::class)->create($data);
        }

        $this->testObj = new CustomerSearch;
    }

    public function test_search_all_customers()
    {
        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(5, $res);
    }

    public function test_search_name()
    {
        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'name'      => 'vaccume'
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(2, $res);
    }

    public function test_search_name_again()
    {
        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'name'      => 'randomStr'
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(0, $res);
    }

    public function test_search_city()
    {
        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'city'      => 'New York',
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(2, $res);
    }

    public function test_search_city_again()
    {
        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'city'      => 'NewYork',
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(0, $res);
    }

    public function test_search_equipment()
    {
        $sys = factory(SystemTypes::class)->create();
        for($i = 1; $i < 4; $i++)
        {
            factory(CustomerSystems::class)->create([
                'sys_id' => $sys->sys_id,
                'cust_id' => $i,
            ]);
        }

        $searchData = new CustomerSearchRequest([
            'perPage'   => 25,
            'sortField' => 'name',
            'sortType'  => 'asc',
            'equipment' => $sys->sys_id,
        ]);
        $res = $this->testObj->search($searchData);

        $this->assertCount(3, $res);
    }

    public function test_search_id()
    {
        $cust = factory(Customers::class)->create();

        $res = $this->testObj->searchID($cust->cust_id);
        $this->assertEquals($cust->toArray(), $res->toArray());
    }
}
