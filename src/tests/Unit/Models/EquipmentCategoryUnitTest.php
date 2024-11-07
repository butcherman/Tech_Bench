<?php

namespace Tests\Unit\Models;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Tests\TestCase;

class EquipmentCategoryUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = EquipmentCategory::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_equipment_type_relationship(): void
    {
        $equip = EquipmentType::factory()
            ->count(5)
            ->create(['cat_id' => $this->model->cat_id]);

        $this->assertEquals(
            $equip->sortBy('name')
                ->values()
                ->toArray(),
            $this->model
                ->EquipmentType
                ->toArray()
        );
    }
}
