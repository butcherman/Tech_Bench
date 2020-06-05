<?php

namespace Tests\Unit\Equipment;

use App\Domains\Equipment\SetEquipmentDataFields;
use App\SystemDataFieldTypes;
use Tests\TestCase;
use App\SystemDataFields;

class SetEquipmentDataFieldsTest extends TestCase
{
    public function test_edit_existing_field()
    {
        $testField = factory(SystemDataFieldTypes::class)->create();

        $res = (new SetEquipmentDataFields)->editExistingField($testField->data_type_id, $newName = 'New Name', $hidden = true);

        $this->assertTrue($res);
        $this->assertDatabaseHas('system_data_field_types', ['data_type_id' => $testField->data_type_id, 'name' => $newName, 'hidden' => $hidden]);
    }

    public function test_delete_field()
    {
        $testField = factory(SystemDataFieldTypes::class)->create();

        $res = (new SetEquipmentDataFields)->deleteField($testField->data_type_id);

        $this->assertTrue($res);
        $this->assertDatabaseMissing('system_data_field_types', ['data_type_id' => $testField->data_type_id]);
    }

    public function test_delete_field_fail()
    {
        $testField = factory(SystemDataFieldTypes::class)->create();
        factory(SystemDataFields::class)->create([
            'data_type_id' => $testField->data_type_id,
        ]);

        $res = (new SetEquipmentDataFields)->deleteField($testField->data_type_id);

        $this->assertFalse($res);
        $this->assertDatabaseHas('system_data_field_types', ['data_type_id' => $testField->data_type_id]);
    }
}
