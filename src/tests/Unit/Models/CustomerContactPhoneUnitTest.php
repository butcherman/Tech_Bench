<?php

namespace Tests\Unit\Models;

use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Tests\TestCase;

class CustomerContactPhoneUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = CustomerContactPhone::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('formatted', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_phone_number_type_relationship(): void
    {
        $shouldBe = PhoneNumberType::find(
            $this->model->PhoneNumberType->phone_type_id
        );

        $this->assertEquals(
            $shouldBe->toArray(),
            $this->model->PhoneNumberType->toArray()
        );
    }
}
