<?php

namespace Tests\Unit\Models;

use App\Models\DataField;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use Tests\TestCase;

class EquipmentTypeUnitTest extends TestCase
{
    protected $equip;

    public function setUp():void
    {
        parent::setUp();

        $this->equip = EquipmentType::factory()->create();
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals('equip_id', $this->equip->getRouteKeyName());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $owner = EquipmentCategory::where('cat_id', $this->equip->cat_id)->first();
        $this->assertEquals($this->equip->EquipmentCategory->name, $owner->name);

        DataField::factory()->create([
            'equip_id' => $this->equip->equip_id,
            'type_id' => 1,
            'order' => 1,
        ]);

        $this->assertEquals($this->equip->DataFieldType[0]->name, 'IP Address');
    }
}
