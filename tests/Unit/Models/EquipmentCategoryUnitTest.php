<?php

namespace Tests\Unit\Models;

use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Tests\TestCase;

class EquipmentCategoryUnitTest extends TestCase
{
    protected $category;

    public function setUp(): void
    {
        parent::setUp();

        $this->category = EquipmentCategory::factory()->create();
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals('cat_id', $this->category->getRouteKeyName());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $equip = EquipmentType::factory()->create(['cat_id' => $this->category->cat_id]);

        $this->assertEquals($this->category->EquipmentType[0]->only(['equip_id', 'name', 'cat_id']), $equip->only(['equip_id', 'name', 'cat_id']));
    }
}
