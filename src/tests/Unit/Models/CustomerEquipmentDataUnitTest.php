<?php

namespace Tests\Unit\Models;

use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\DataFieldType;
use Tests\TestCase;

class CustomerEquipmentDataUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = CustomerEquipmentData::factory()->create();
    }

    /**
     * MOdel Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('field_name', $this->model->toArray());
        $this->assertArrayHasKey('order', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_data_field_relationship()
    {
        $data = DataField::where('field_id', $this->model->field_id)->first();

        $this->assertEquals($data->toArray(), $this->model->DataField->toArray());
    }

    public function test_data_field_type_relationship()
    {
        $data = DataFieldType::where('type_id', $this->model->DataField->type_id)
            ->first();

        $this->assertEquals($data->toArray(), $this->model->DataFieldType->toArray());
    }
}
