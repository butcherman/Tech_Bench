<?php

namespace Tests\Unit\Models;

use App\Models\DataField;
use App\Models\DataFieldType;
use Tests\TestCase;

class DataFieldUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = DataField::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_data_field_type_relationship(): void
    {
        $type = DataFieldType::find($this->model->type_id);

        $this->assertEquals($type, $this->model->DataFieldType);
    }
}
