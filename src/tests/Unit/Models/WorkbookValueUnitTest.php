<?php

namespace Tests\Unit\Models;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookValue;
use Tests\TestCase;

class WorkbookValueUnitTest extends TestCase
{
    /** @var WorkbookValue */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = WorkbookValue::factory()->create();
    }

    /**
     * Route Key Name
     */
    public function test_get_route_key_name(): void
    {
        $this->assertEquals('index', $this->model->getRouteKeyName());
    }

    /**
     * Relationships
     */
    public function test_customer_workbook_relationship(): void
    {
        $workbook = CustomerEquipmentWorkbook::find($this->model->wb_id);

        $this->assertEquals(
            $workbook->toArray(),
            $this->model->CustomerWorkbook->toArray()
        );
    }
}
