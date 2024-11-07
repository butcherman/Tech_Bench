<?php

namespace Tests\Unit\Services\Equipment;

use App\Exceptions\Database\RecordInUseException;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Services\Equipment\EquipmentService;
use Tests\TestCase;

class EquipmentServiceUnitTest extends TestCase
{
    public function test_create_category(): void
    {
        $newCat = EquipmentCategory::factory()->make();

        $testObj = new EquipmentService;
        $data = $testObj->createCategory(collect($newCat->toArray()));

        $this->assertEquals($newCat->toArray(), $data->makeHidden('cat_id')->toArray());
        $this->assertDatabaseHas('equipment_categories', $newCat->toArray());
    }

    public function test_update_category(): void
    {
        $cat = EquipmentCategory::factory()->create();
        $changed = EquipmentCategory::factory()->make();

        $testObj = new EquipmentService;
        $data = $testObj->updateCategory(collect($changed->toArray()), $cat);

        $this->assertEquals($changed->only(['name']), $data->only(['name']));
        $this->assertDatabaseHas('equipment_categories', [
            'cat_id' => $cat->cat_id,
            'name' => $changed->name,
        ]);
    }

    public function test_destroy_category(): void
    {
        $cat = EquipmentCategory::factory()->create();

        $testObj = new EquipmentService;
        $testObj->destroyCategory($cat);

        $this->assertDatabaseMissing('equipment_categories', $cat->toArray());
    }

    public function test_destroy_category_in_use(): void
    {
        $cat = EquipmentCategory::factory()->create();
        EquipmentType::factory()->create(['cat_id' => $cat->cat_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new EquipmentService;
        $testObj->destroyCategory($cat);

        $this->assertDatabaseHas('equipment_categories', $cat->toArray());
    }
}
