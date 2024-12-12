<?php

namespace Tests\Unit\Actions\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Models\Customer;
use Tests\TestCase;

class CustomerSearchUnitTest extends TestCase
{
    /*
     *   Setup will populate five specific customers to search
     */
    protected function setUp(): void
    {
        parent::setUp();

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

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_search_all_results(): void
    {
        $searchData = [
            'perPage' => 25,
            'searchFor' => null,
            'page' => 1,
        ];

        $testObj = new CustomerSearch;
        $res = $testObj(collect($searchData));

        $this->assertCount(5, $res->toArray()['data']);
    }

    public function test_search_for_id(): void
    {
        $searchData = [
            'cust_id' => 5,
        ];

        $testObj = new CustomerSearch;
        $res = $testObj(collect($searchData));

        $this->assertEquals($res->toArray(), Customer::find(5)->toArray());
    }

    public function test_search_for_id_bad_id(): void
    {
        $searchData = [
            'cust_id' => 55555,
        ];

        $testObj = new CustomerSearch;
        $res = $testObj(collect($searchData));

        $this->assertNull($res);
    }
}
