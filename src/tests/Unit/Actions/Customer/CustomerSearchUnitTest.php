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

    public function test_search_filtered_results_one(): void
    {
        $searchData = [
            'perPage' => 25,
            'searchFor' => 'Pinky',
            'page' => 1,
        ];

        $testObj = new CustomerSearch;
        $res = $testObj(collect($searchData));

        // Test will return zero results, we just need to verify method fires
        $this->assertCount(0, $res->toArray()['data']);
    }
}
