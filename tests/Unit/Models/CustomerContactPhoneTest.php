<?php

namespace Tests\Unit\Models;

use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use Tests\TestCase;

class CustomerContactPhoneTest extends TestCase
{
    public function test_phone_number_type_relationship()
    {
        $phone = CustomerContactPhone::factory()->create(['phone_number' => 8165551212, 'phone_type_id' => 1]);
        $this->assertEquals($phone->PhoneNumberType->toArray(), ['description' => 'Home', 'icon_class' => 'fas fa-home']);
    }

    public function test_get_formatted_attribute()
    {
        $phone = CustomerContactPhone::factory()->create(['phone_number' => 8165551212]);
        $this->assertEquals($phone->formatted, '(816) 555-1212');
    }
}
