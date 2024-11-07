<?php

namespace Tests\Unit\Services\Equipment;

use App\Exceptions\Database\RecordInUseException;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Services\Equipment\EquipmentDataService;
use Tests\TestCase;

class EquipmentDataServiceUnitTest extends TestCase
{
    public function test_create_data_type(): void
    {
        $newType = DataFieldType::factory()->make();

        $testObj = new EquipmentDataService;
        $field = $testObj->createDataType(collect($newType->toArray()));

        $this->assertEquals(
            $newType->toArray(),
            $field->makeHidden('type_id')->toArray()
        );
        $this->assertDatabaseHas('data_field_types', $newType->toArray());
    }

    public function test_update_data_type(): void
    {
        $type = DataFieldType::factory()->create();
        $newData = DataFieldType::factory()->create();

        $testObj = new EquipmentDataService;
        $field = $testObj->updateDataType(collect($newData->toArray()), $type);

        $this->assertEquals(
            $newData->only(['name', 'pattern', 'pattern_error']),
            $field->only(['name', 'pattern', 'pattern_error'])
        );
        $this->assertDatabaseHas('data_field_types', [
            'type_id' => $type->type_id,
            'name' => $newData->name,
        ]);
    }

    public function test_destroy_data_type(): void
    {
        $type = DataFieldType::factory()->create();

        $testObj = new EquipmentDataService;
        $testObj->destroyDataType($type);

        $this->assertDatabaseMissing('data_field_types', $type->toArray());
    }

    public function test_destroy_data_type_in_use(): void
    {
        $type = DataFieldType::factory()->create();
        DataField::factory()->create(['type_id' => $type->type_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new EquipmentDataService;
        $testObj->destroyDataType($type);

        $this->assertDatabaseHas('data_field_types', $type->toArray());
    }
}
