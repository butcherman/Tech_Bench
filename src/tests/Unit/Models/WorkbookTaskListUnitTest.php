<?php

namespace Tests\Unit\Models;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTaskList;
use Tests\TestCase;

class WorkbookTaskListUnitTest extends TestCase
{
    /** @var WorkbookTaskList */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = WorkbookTaskList::factory()->create();
    }

    /**
     * Get Route Key Name
     */
    public function test_get_route_key_name(): void
    {
        $this->assertEquals('index', $this->model->getRouteKeyName());
    }

    /**
     * Model Relationships
     */
    public function test_customer_workbook_relationship(): void
    {
        $workbook = CustomerEquipmentWorkbook::find($this->model->wb_id);

        $this->assertEquals($workbook->toArray(), $this->model->CustomerWorkbook->toArray());
    }
}
