<?php

namespace Tests\Unit\Services\Customer;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentWorkbook;
use App\Models\EquipmentWorkbook;
use App\Models\WorkbookTableValue;
use App\Models\WorkbookTaskList;
use App\Models\WorkbookTaskListItem;
use App\Models\WorkbookValue;
use App\Services\Customer\CustomerWorkbookService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerWorkbookServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createWorkbook()
    |---------------------------------------------------------------------------
    */
    public function test_create_workbook(): void
    {
        $equip = CustomerEquipment::factory()->create();
        EquipmentWorkbook::factory()->create([
            'equip_id' => $equip->equip_id,
        ]);

        $testObj = new CustomerWorkbookService;
        $testObj->createWorkbook($equip);

        $this->assertDatabaseHas('customer_equipment_workbooks', [
            'cust_id' => $equip->cust_id,
            'cust_equip_id' => $equip->cust_equip_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateWorkbook()
    |---------------------------------------------------------------------------
    */
    public function test_update_workbook(): void
    {
        $workbookParent = EquipmentWorkbook::factory()->create();
        $custEquip = CustomerEquipment::factory()->create([
            'equip_id' => $workbookParent->equip_id,
        ]);
        $workbook = CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $custEquip->cust_id,
            'cust_equip_id' => $custEquip->cust_equip_id,
        ]);

        $skel = $workbook->wb_skeleton;
        $ver = $workbook->wb_version;

        $workbookParent->workbook_data = json_decode('{"body":[],"header":[]}');
        $workbookParent->version_hash = 'newHash';
        $workbookParent->save();

        $custEquip->refresh();

        $testObj = new CustomerWorkbookService;
        $testObj->updateWorkbook($custEquip);

        $workbook->refresh();

        $this->assertNotEquals($skel, $workbook->wb_skeleton);
        $this->assertNotEquals($ver, $workbook->wb_version);
    }

    /*
    |---------------------------------------------------------------------------
    | publishWorkbook();
    |---------------------------------------------------------------------------
    */
    public function test_publish_workbook(): void
    {
        $custEquip = CustomerEquipment::factory()->create();
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $custEquip->cust_id,
            'cust_equip_id' => $custEquip->cust_equip_id,
        ]);

        $expire = Carbon::now()->format('Y-m-d');

        $testObj = new CustomerWorkbookService;
        $testObj->publishWorkbook(
            $custEquip->load('EquipmentWorkbook'),
            $expire
        );

        $equipWb = CustomerEquipmentWorkbook::where(
            'cust_equip_id',
            $custEquip->cust_equip_id
        )->first();

        $this->assertNotNull($equipWb->publish_until);
    }

    /*
    |---------------------------------------------------------------------------
    | unPublishWorkbook()
    |---------------------------------------------------------------------------
    */
    public function test_unpublish_workbook(): void
    {
        $custEquip = CustomerEquipment::factory()->create();
        CustomerEquipmentWorkbook::factory()->create([
            'cust_id' => $custEquip->cust_id,
            'cust_equip_id' => $custEquip->cust_equip_id,
            'publish_until' => Carbon::now()->addDays(30),
        ]);

        $testObj = new CustomerWorkbookService;
        $testObj->unPublishWorkbook($custEquip->load('EquipmentWorkbook'));

        $equipWb = CustomerEquipmentWorkbook::where(
            'cust_equip_id',
            $custEquip->cust_equip_id
        )->first();

        $this->assertNull($equipWb->publish_until);
    }

    /*
    |---------------------------------------------------------------------------
    | getAllWorkbookValues()
    |---------------------------------------------------------------------------
    */
    public function test_get_all_workbook_values(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => true,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => true,
        ]);
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => false,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => false,
        ]);

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getAllWorkbookValues($workbook);

        $this->assertCount(20, $res);
    }

    public function test_get_all_workbook_values_with_private(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => true,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => true,
        ]);
        WorkbookValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => false,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'public' => false,
        ]);

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getAllWorkbookValues($workbook, true);

        $this->assertCount(40, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getWorkbookTableValues()
    |---------------------------------------------------------------------------
    */
    public function test_get_workbook_table_values(): void
    {
        $publicTableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $privateTableIndex = 'a905cdb0-dfaf-4d2e-9b0a-f86f01e8a7fb';

        $workbook = CustomerEquipmentWorkbook::factory()->create();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $publicTableIndex,
            'public' => true,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $privateTableIndex,
            'public' => false,
        ]);

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getWorkbookTableValues($workbook, $publicTableIndex);

        $this->assertCount(10, $res);
        $this->assertArrayNotHasKey('index', $res[0]);
    }

    public function test_get_workbook_table_values_with_index(): void
    {
        $publicTableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $privateTableIndex = 'a905cdb0-dfaf-4d2e-9b0a-f86f01e8a7fb';

        $workbook = CustomerEquipmentWorkbook::factory()->create();

        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $publicTableIndex,
            'public' => true,
        ]);
        WorkbookTableValue::factory()->count(10)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $privateTableIndex,
            'public' => false,
        ]);

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getWorkbookTableValues($workbook, $publicTableIndex, true);

        $this->assertCount(10, $res);
        $this->assertArrayHasKey('index', $res[0]);
    }

    /*
    |---------------------------------------------------------------------------
    | saveWorkbookValue()
    |---------------------------------------------------------------------------
    */
    public function test_save_workbook_value(): void
    {
        $data = [
            'value' => 'random val',
            'index' => Str::uuid(),
            'public' => false,
            'value_type' => 'input',
        ];
        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $testObj = new CustomerWorkbookService;
        $testObj->saveWorkbookValue($workbook, collect($data));

        $this->assertDatabaseHas('workbook_values', [
            'wb_id' => $workbook->wb_id,
            'index' => $data['index'],
            'value' => $data['value'],
            'public' => $data['public'],
        ]);
    }

    public function test_save_workbook_value_table(): void
    {
        $data = [
            'value' => 'random val',
            'public' => false,
            'value_type' => 'data-table',
            'table_index' => Str::uuid(),
            'row_index' => Str::uuid(),
            'column_name' => 'Table Col',
        ];
        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $testObj = new CustomerWorkbookService;
        $testObj->saveWorkbookValue($workbook, collect($data));

        $this->assertDatabaseHas('workbook_table_values', [
            'wb_id' => $workbook->wb_id,
            'table_index' => $data['table_index'],
            'row_index' => $data['row_index'],
            'value' => $data['value'],
            'public' => $data['public'],
        ]);
    }

    public function test_save_workbook_value_task_list(): void
    {
        $data = [
            'list_index' => $listIndex = Str::uuid(),
            'locked' => false,
            'public' => true,
            'workbook_task_list_item' => [
                [
                    'list_index' => $listIndex,
                    'list_item' => 'item 1',
                    'order' => 0,
                ],
                [
                    'list_index' => $listIndex,
                    'list_item' => 'item 2',
                    'order' => 1,
                ],
                [
                    'list_index' => $listIndex,
                    'list_item' => 'item 3',
                    'order' => 2,
                ],
            ],
            'value_type' => 'task-list',
        ];

        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $testObj = new CustomerWorkbookService;
        $testObj->saveWorkbookValue($workbook, collect($data));

        $this->assertDatabaseHas('workbook_task_lists', [
            'wb_id' => $workbook->wb_id,
            'list_index' => $data['list_index'],
        ]);
    }

    public function test_save_workbook_value_task_list_item(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $taskList = WorkbookTaskList::factory()
            ->create(['wb_id' => $workbook->wb_id]);

        $data = [
            'completed' => true,
            'completed_by' => 'Billy Bob',
            'file_id' => null,
            'list_index' => $taskList->list_index,
            'list_item' => 'item 1',
            'order' => 0,
            'value_type' => 'task-list-item',
        ];

        $testObj = new CustomerWorkbookService;
        $testObj->saveWorkbookValue($workbook, collect($data));

        $this->assertDatabaseHas('workbook_task_list_items', [
            'list_id' => $taskList->list_id,
            'list_item' => $data['list_item'],
            'order' => $data['order'],
            'completed_by' => $data['completed_by'],
        ]);
    }

    public function test_save_workbook_value_task_delete_list_item(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $taskList = WorkbookTaskList::factory()
            ->create(['wb_id' => $workbook->wb_id]);

        $data = [
            'completed' => true,
            'completed_by' => 'Billy Bob',
            'file_id' => null,
            'list_index' => $taskList->list_index,
            'list_item' => 'item 1',
            'order' => 0,
            'value_type' => 'task-list-item',
            'delete_item' => true,
        ];

        $testObj = new CustomerWorkbookService;
        $testObj->saveWorkbookValue($workbook, collect($data));

        $res = WorkbookTaskListItem::where('list_id', $taskList->list_id)
            ->where('list_item', $data['list_item'])
            ->withTrashed()
            ->get();

        $this->assertSoftDeleted($res);
    }

    /*
    |---------------------------------------------------------------------------
    | importTableData()
    |---------------------------------------------------------------------------
    */
    public function test_import_table_data(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = Str::uuid();

        $testObj = new CustomerWorkbookService;
        $res = $testObj->importTableData(
            $workbook,
            $tableIndex,
            $this->getTableImportData(),
            true
        );

        $count = WorkbookTableValue::where('table_index', $tableIndex)->get();

        $this->assertTrue($res);
        $this->assertCount(8, $count);
    }

    public function test_import_table_data_has_validation_errors(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = Str::uuid();
        $importData = $this->getTableImportData();

        $importData[0]['Public Col 1']['valid'] = false;

        $testObj = new CustomerWorkbookService;
        $res = $testObj->importTableData(
            $workbook,
            $tableIndex,
            $importData,
            true
        );

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | deleteTableRow()
    |---------------------------------------------------------------------------
    */
    public function test_delete_table_row(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = Str::uuid();
        $rowOneIndex = Str::uuid();
        $rowTwoIndex = Str::uuid();

        WorkbookTableValue::factory()->count(5)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowOneIndex,
        ]);
        WorkbookTableValue::factory()->count(5)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowTwoIndex,
        ]);

        $testObj = new CustomerWorkbookService;
        $testObj->deleteTableRow($workbook, $tableIndex, $rowOneIndex);

        $this->assertDatabaseMissing('workbook_table_values', [
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowOneIndex,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | deleteTableData()
    |---------------------------------------------------------------------------
    */
    public function test_delete_table_data(): void
    {
        $workbook = CustomerEquipmentWorkbook::factory()->create();
        $tableIndex = Str::uuid();
        $rowOneIndex = Str::uuid();
        $rowTwoIndex = Str::uuid();

        WorkbookTableValue::factory()->count(5)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowOneIndex,
        ]);
        WorkbookTableValue::factory()->count(5)->create([
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
            'row_index' => $rowTwoIndex,
        ]);

        $testObj = new CustomerWorkbookService;
        $testObj->deleteTableData($workbook, $tableIndex);

        $this->assertDatabaseMissing('workbook_table_values', [
            'wb_id' => $workbook->wb_id,
            'table_index' => $tableIndex,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | getTableHeaders()
    |---------------------------------------------------------------------------
    */
    public function test_get_table_headers(): void
    {
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getTableHeaders($workbook, $tableIndex);

        $shouldBe = [
            'Public Col 1',
            'Public Col 2',
            'Public Col 3',
            'Public Col 4',
        ];

        $this->assertEquals($res, $shouldBe);
    }

    /*
    |---------------------------------------------------------------------------
    | getTableHeaderData()
    |---------------------------------------------------------------------------
    */
    public function test_get_table_header_data(): void
    {
        $tableIndex = '4e2eae40-b892-4509-818a-b03191dbc237';
        $workbook = CustomerEquipmentWorkbook::factory()->create();

        $testObj = new CustomerWorkbookService;
        $res = $testObj->getTableHeaderData($workbook, $tableIndex);

        $shouldBe = [
            [
                'name' => 'Public Col 1',
                'type' => 'string',
            ],
            [
                'list' => [
                    0 => 'Pub 1',
                    1 => 'Pub 2',
                ],
                'name' => 'Public Col 2',
                'type' => 'enum',
            ],
            [
                'name' => 'Public Col 3',
                'type' => 'boolean',
            ],
            [
                'name' => 'Public Col 4',
                'type' => 'integer',
            ],
        ];

        $this->assertEquals($res, $shouldBe);
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    protected function getTableImportData(): array
    {
        return [
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'John Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 1',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => false,
                    'validation_error' => null,
                ],
                'Public Col 4' => [
                    'valid' => true,
                    'data_type' => 'integer',
                    'value' => 12345,
                    'validation_error' => null,
                ],
            ],
            [
                'Public Col 1' => [
                    'valid' => true,
                    'data_type' => 'string',
                    'value' => 'Jane Doe',
                    'validation_error' => null,
                ],
                'Public Col 2' => [
                    'valid' => true,
                    'data_type' => 'enum',
                    'value' => 'Pub 2',
                    'validation_error' => null,
                ],
                'Public Col 3' => [
                    'valid' => true,
                    'data_type' => 'boolean',
                    'value' => true,
                    'validation_error' => null,
                ],
                'Public Col 4' => [
                    'valid' => true,
                    'data_type' => 'integer',
                    'value' => 54321,
                    'validation_error' => null,
                ],
            ],
        ];
    }
}
