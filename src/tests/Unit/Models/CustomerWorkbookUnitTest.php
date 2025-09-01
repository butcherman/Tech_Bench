<?php

namespace Tests\Unit\Models;

use App\Models\CustomerWorkbook;
use App\Models\CustomerWorkbookValue;
use Tests\TestCase;

class CustomerWorkbookUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = CustomerWorkbook::factory()->create();

        CustomerWorkbookValue::create([
            'wb_id' => $this->model->wb_id,
            'index' => 'index_1',
            'value' => 'value 1',
        ]);
        CustomerWorkbookValue::create([
            'wb_id' => $this->model->wb_id,
            'index' => 'index_2',
            'value' => 'value 2',
        ]);

    }

    public function test_get_route_key_name(): void
    {
        $this->assertEquals('wb_hash', $this->model->getRouteKeyName());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_workbook_values_relationship(): void
    {
        $values = CustomerWorkbookValue::where('wb_id', $this->model->wb_id)->get();

        $this->assertEquals($values->toArray(), $this->model->WorkbookValues->toArray());
    }
}
