<?php

namespace Tests\Unit\Models;

use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Tests\TestCase;

class CustomerContactPhoneUnitTest extends TestCase
{
    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $model = CustomerContactPhone::factory()->create(['phone_type_id' => 1]);

        $this->assertEquals($model->PhoneNumberType->toArray(), PhoneNumberType::find(1)->toArray());
    }

    /**
     * test Attributes
     */
    public function test_additional_attributes()
    {
        $model = CustomerContactPhone::factory()->create(['phone_number' => 5415551212]);

        $this->assertEquals($model->formatted, '(541) 555-1212');
    }
}
