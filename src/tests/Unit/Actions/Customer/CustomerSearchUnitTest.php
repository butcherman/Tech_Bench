<?php

namespace Tests\Unit\Actions\Customer;

use App\Actions\Customer\CustomerSearch;
use App\Models\Customer;
use Tests\TestCase;

class CustomerSearchUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_empty_search(): void
    {
        Customer::factory()->count(10)->create();

        $data = collect([
            'page' => 1,
            'perPage' => 25,
            'searchFor' => '',
        ]);

        $testObj = new CustomerSearch;
        $res = $testObj($data);

        $this->assertCount(10, $res);
    }

    public function test_filtered_search(): void
    {
        Customer::factory()->count(10)->create();

        $data = collect([
            'page' => 1,
            'perPage' => 25,
            'searchFor' => 'something',
        ]);

        $testObj = new CustomerSearch;
        $res = $testObj($data);

        $this->assertEquals($res->toArray()['data'], []);
    }

    public function test_search_by_id(): void
    {
        $test = Customer::factory()->create();

        $data = collect([
            'cust_id' => $test->cust_id,
        ]);

        $testObj = new CustomerSearch;
        $res = $testObj($data);

        $this->assertEquals(
            $res->makeHidden('deleted_at')->toArray(),
            $test->toArray()
        );
    }

    public function test_search_by_id_bad_id(): void
    {
        $data = collect([
            'cust_id' => 6688846993,
        ]);

        $testObj = new CustomerSearch;
        $res = $testObj($data);

        $this->assertFalse($res);
    }
}
