<?php

namespace Tests\Unit\Models;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Tests\TestCase;

class EquipmentTypeUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = EquipmentType::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_equipment_category_relationship(): void
    {
        $category = EquipmentCategory::find($this->model->cat_id);

        $this->assertEquals(
            $category->toArray(),
            $this->model->EquipmentCategory->toArray()
        );
    }

    // public function test_data_field_type_relationship(): void
    // {
    //     $typeList = DataFieldType::factory()->count(5)->create();
    //     $typeList->each(function ($type) {
    //         DataField::create([
    //             'equip_id' => $this->model->equip_id,
    //             'type_id' => $type->type_id,
    //             'order' => 1,
    //         ]);
    //     });

    //     $this->assertEquals(
    //         $typeList->toArray(),
    //         $this->model->DataFieldType->toArray()
    //     );
    // }
}
