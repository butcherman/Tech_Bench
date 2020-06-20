<?php

namespace Tests\Unit\PhoneNumbers;

use App\Domains\PhoneNumbers\GetPhoneNumberTypes;
use Tests\TestCase;

class GetPhoneNumberTypesTest extends TestCase
{
    public function test_execute()
    {
        $res = (new GetPhoneNumberTypes)->execute();
        $defaultData = [
            ['phone_type_id' => 1, 'description' => 'Home',   'icon_class' => 'fas fa-home'],
            ['phone_type_id' => 2, 'description' => 'Work',   'icon_class' => 'fas fa-briefcase'],
            ['phone_type_id' => 3, 'description' => 'Mobile', 'icon_class' => 'fas fa-mobile-alt'],
        ];

        $this->assertEquals($res->toArray(), $defaultData);
    }
}
