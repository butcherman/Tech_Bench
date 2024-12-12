<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\TechTipEquipment;
use Tests\TestCase;

class EquipmentTypeUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
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

    public function test_data_field_type_relationship(): void
    {
        $typeList = DataFieldType::factory()->count(5)->create();
        $typeList->each(function ($type) {
            DataField::create([
                'equip_id' => $this->model->equip_id,
                'type_id' => $type->type_id,
                'order' => 1,
            ]);
        });

        $this->assertEquals(
            $typeList->toArray(),
            $this->model->DataFieldType->toArray()
        );
    }

    public function test_customer_relationship(): void
    {
        $cust = Customer::factory()->create();
        CustomerEquipment::create([
            'cust_id' => $cust->cust_id,
            'equip_id' => $this->model->equip_id,
        ]);

        $this->assertEquals(
            $cust->toArray(),
            $this->model->Customer[0]->toArray()
        );
    }

    public function test_tech_tip_relationship(): void
    {
        $tip = TechTip::factory()->create();
        TechTipEquipment::create([
            'tip_id' => $tip->tip_id,
            'equip_id' => $this->model->equip_id,
        ]);

        $this->assertEquals(
            $tip->toArray(),
            $this->model
                ->TechTip[0]
                ->makeHidden(['allow_comments', 'laravel_through_key'])
                ->toArray()
        );
    }
}
