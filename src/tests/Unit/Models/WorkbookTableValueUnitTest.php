<?php

namespace Tests\Unit\Models;

use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTableValue;
use Tests\TestCase;

class WorkbookTableValueUnitTest extends TestCase
{
    /** @var WorkbookTableValue */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = WorkbookTableValue::factory()->create();
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
