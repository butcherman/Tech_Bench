<?php

namespace Tests\Unit\Services\Report\Customer;

use App\Facades\CacheData;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\EquipmentType;
use App\Services\Report\Customer\CustomerEquipmentReport;
use Tests\TestCase;

class CustomerEquipmentReportUnitTest extends TestCase
{
    protected $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObj = new CustomerEquipmentReport;
    }

    /*
    |---------------------------------------------------------------------------
    | getReportGroup()
    |---------------------------------------------------------------------------
    */
    public function test_get_report_group(): void
    {
        $shouldBe = 'Customer';

        $res = $this->testObj->getReportGroup();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getReportParamForm()
    |---------------------------------------------------------------------------
    */
    public function test_get_report_param_form(): void
    {
        $shouldBe = 'CustomerEquipmentReportForm';

        $res = $this->testObj->getReportParamForm();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getReportParamProps()
    |---------------------------------------------------------------------------
    */
    public function test_get_report_param_props(): void
    {
        $shouldBe = [
            'equipment-types' => CacheData::equipmentCategorySelectBox(),
        ];

        $res = $this->testObj->getReportParamProps();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getReportDataPage()
    |---------------------------------------------------------------------------
    */
    public function test_get_report_data_page(): void
    {
        $shouldBe = 'CustomerEquipmentReport';

        $res = $this->testObj->getReportDataPage();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getValidationParams()
    |---------------------------------------------------------------------------
    */
    public function test_get_validation_params(): void
    {
        $shouldBe = [
            'equip_id' => ['required', 'numeric'],
        ];

        $res = $this->testObj->getValidationParams();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | generateReportData()
    |---------------------------------------------------------------------------
    */
    public function test_generate_report_data(): void
    {
        Customer::factory()->count(20)->create();

        $custList = Customer::inRandomOrder()->limit(5)->get();
        $equipment = EquipmentType::factory()->create();

        foreach ($custList as $cust) {
            CustomerEquipment::factory()->create([
                'cust_id' => $cust->cust_id,
                'equip_id' => $equipment->equip_id,
            ]);
        }

        $data = [
            'equip_id' => $equipment->equip_id,
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(5, $res['custList']);
        $this->assertEquals($equipment->name, $res['equipName']);
    }
}
