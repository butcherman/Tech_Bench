<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerAlert;
use Tests\TestCase;

class CustomerAlertUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = CustomerAlert::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_customer_relationship(): void
    {
        $shouldBe = Customer::find($this->model->cust_id);

        $this->assertEquals(
            $shouldBe->toArray(),
            $this->model->Customer->toArray()
        );
    }
}
