<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\WorkbookTableValue;
use App\Models\WorkbookValue;
use Tests\TestCase;

class CustomerEquipmentWorkbookUnitTest extends TestCase
{
    /** @var CustomerEquipmentWorkbook */
    protected $model;

    /** @var Customer */
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->model = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $this->customer->cust_id,
        ]);
    }

    /**
     * Get Route Key Name
     */
    public function test_get_route_key_name(): void
    {
        $this->assertEquals('wb_hash', $this->model->getRouteKeyName());
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('published', $this->model->toArray());
        $this->assertArrayHasKey('publish_until_raw', $this->model->toArray());
        $this->assertArrayHasKey('parsed_workbook', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_workbook_values_relationship(): void
    {
        $values = WorkbookValue::factory()->count(10)->create([
            'wb_id' => $this->model->wb_id,
        ]);

        $this->assertEquals(
            $values->toArray(),
            $this->model->WorkbookValues->toArray()
        );
    }

    public function test_workbook_table_values_relationship(): void
    {
        $values = WorkbookTableValue::factory()->count(15)->create([
            'wb_id' => $this->model->wb_id,
        ]);

        $this->assertEquals(
            $values->toArray(),
            $this->model->WorkbookTableValues->toArray()
        );
    }

    public function test_public_workbook_values_relationship(): void
    {
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $this->model->wb_id,
            'public' => true,
        ]);
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $this->model->wb_id,
            'public' => false,
        ]);

        $this->assertCount(10, $this->model->PublicWorkbookValues);
    }

    public function test_public_workbook_table_values_relationship(): void
    {
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $this->model->wb_id,
            'public' => true,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $this->model->wb_id,
            'public' => false,
        ]);

        $this->assertCount(10, $this->model->PublicWorkbookTableValues);
    }

    public function test_customer_relationship(): void
    {
        $this->assertEquals(
            $this->customer->makeHidden(['vpn_id'])->toArray(),
            $this->model->Customer->makeHidden(['vpn_id'])->toArray()
        );
    }

    public function test_customer_equipment_relationship(): void
    {
        $equipment = CustomerEquipment::find($this->model->cust_equip_id);

        $this->assertEquals(
            $equipment->toArray(),
            $this->model->CustomerEquipment->toArray()
        );
    }
}
