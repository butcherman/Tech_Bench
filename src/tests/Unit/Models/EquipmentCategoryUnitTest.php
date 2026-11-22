<?php

namespace Tests\Unit\Models;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Tests\TestCase;

class EquipmentCategoryUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = EquipmentCategory::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Scopes
    |---------------------------------------------------------------------------
    */
    public function test_equipment_scope(): void
    {
        $this->assertEquals(
            EquipmentCategory::with('EquipmentType')->get(),
            EquipmentCategory::equipment()->get(),
        );
    }

    public function test_public_equipment_scope(): void
    {
        $this->assertEquals(
            EquipmentCategory::with(['EquipmentType' => function ($q) {
                $q->where('allow_public_tip', true);
            }])
                ->get()
                ->toArray(),
            EquipmentCategory::publicEquipment()->get()->toArray(),
        );
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
