<?php

namespace Tests\Unit\Services\Misc;

use App\Exceptions\Database\RecordInUseException;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use App\Services\Misc\PhoneTypeService;
use Tests\TestCase;

class PhoneNumberServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createPhoneType()
    |---------------------------------------------------------------------------
    */
    public function test_create_phone_type(): void
    {
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $testObj = new PhoneTypeService;
        $res = $testObj->createPhoneType(collect($data));

        $this->assertEquals($data, $res->toArray());

        $this->assertDatabaseHas('phone_number_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | updatePhoneType()
    |---------------------------------------------------------------------------
    */
    public function test_update_phone_type(): void
    {
        $phoneType = PhoneNumberType::find(1);
        $data = [
            'description' => 'New Test Description',
            'icon_class' => 'fa-home',
        ];

        $testObj = new PhoneTypeService;
        $res = $testObj->updatePhoneType(collect($data), $phoneType);

        $this->assertEquals($data, $res->toArray());

        $this->assertDatabaseHas('phone_number_types', [
            'phone_type_id' => $phoneType->phone_type_id,
            'description' => $data['description'],
            'icon_class' => $data['icon_class'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyPhoneType()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_phone_type(): void
    {
        $phoneType = PhoneNumberType::find(1);

        $testObj = new PhoneTypeService;
        $testObj->destroyPhoneType($phoneType);

        $this->assertDatabaseMissing(
            'phone_number_types',
            $phoneType->toArray()
        );
    }

    public function test_destroy_phone_type_in_use(): void
    {
        $phoneType = PhoneNumberType::find(1);
        CustomerContactPhone::factory()
            ->create(['phone_type_id' => $phoneType->phone_type_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new PhoneTypeService;
        $testObj->destroyPhoneType($phoneType);

        $this->assertDatabaseHas('phone_number_types', $phoneType->toArray());
    }
}
