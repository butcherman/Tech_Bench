<?php

namespace Tests\Unit\Services\Report\Customer;

use App\Facades\CacheData;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\EquipmentType;
use App\Services\Report\Customer\CustomerFilesReport;
use Tests\TestCase;

class CustomerFilesReportUnitTest extends TestCase
{
    protected $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObj = new CustomerFilesReport;
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
        $shouldBe = 'CustomerFilesReportForm';

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
            'file-types' => CacheData::fileTypes(),
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
        $shouldBe = 'CustomerFilesReport';

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
            'hasInput' => 'required|string',
            'file_types' => 'required|array',
            'has_equipment' => 'nullable|numeric',
        ];

        $res = $this->testObj->getValidationParams();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | generateReportData()
    |---------------------------------------------------------------------------
    */
    public function test_generate_report_data_has_file_type(): void
    {
        Customer::factory()->count(20)->create();

        $custList = Customer::inRandomOrder()->limit(5)->get();
        $fileType = CustomerFileType::find(1);
        $equip = EquipmentType::factory()->create();

        foreach ($custList as $cust) {
            CustomerFile::factory()->create([
                'cust_id' => $cust->cust_id,
                'file_type_id' => 1,
            ]);

            CustomerEquipment::create([
                'cust_id' => $cust->cust_id,
                'equip_id' => $equip->equip_id,
            ]);
        }

        $data = [
            'hasInput' => 'have',
            'file_types' => [1],
            'has_equipment' => $equip->equip_id,
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(5, $res[$fileType->description]);
    }

    public function test_generate_report_data_missing_file_type(): void
    {
        $allCustomers = Customer::factory()
            ->count(20)
            ->create();

        $custList = Customer::inRandomOrder()->limit(5)->get();
        $fileType = CustomerFileType::find(1);
        $equip = EquipmentType::factory()->create();

        foreach ($allCustomers as $cust) {
            CustomerEquipment::create([
                'cust_id' => $cust->cust_id,
                'equip_id' => $equip->equip_id,
            ]);
        }

        foreach ($custList as $cust) {
            CustomerFile::factory()->create([
                'cust_id' => $cust->cust_id,
                'file_type_id' => 1,
            ]);
        }

        $data = [
            'hasInput' => 'is missing',
            'file_types' => [1],
            'has_equipment' => $equip->equip_id,
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(15, $res[$fileType->description]);
    }
}
