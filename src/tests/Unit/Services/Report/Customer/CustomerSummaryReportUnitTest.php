<?php

namespace Tests\Unit\Services\Report\Customer;

use App\Models\Customer;
use App\Services\Report\Customer\CustomerSummaryReport;
use Tests\TestCase;

class CustomerSummaryReportUnitTest extends TestCase
{
    protected $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObj = new CustomerSummaryReport;
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
        $shouldBe = 'CustomerSummaryReportForm';

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
        $shouldBe = [];

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
        $shouldBe = 'CustomerSummaryReport';

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
            'all_customers' => ['required', 'boolean'],
            'customer_list' => ['nullable', 'array'],
        ];

        $res = $this->testObj->getValidationParams();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | generateReportData()
    |---------------------------------------------------------------------------
    */
    public function test_generate_report_data_all_customers(): void
    {
        Customer::factory()->count(10)->create();

        $data = [
            'all_customers' => true,
            'customer_list' => [],
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(10, $res);

        $this->assertArrayHasKey('name', $res[0]);
        $this->assertArrayHasKey('cust_id', $res[0]);
        $this->assertArrayHasKey('sites', $res[0]);
        $this->assertArrayHasKey('equipment', $res[0]);
        $this->assertArrayHasKey('notes', $res[0]);
        $this->assertArrayHasKey('contacts', $res[0]);
        $this->assertArrayHasKey('files', $res[0]);
    }

    public function test_generate_report_data_some_customers(): void
    {
        $custList = Customer::factory()->count(10)->create();

        $data = [
            'all_customers' => false,
            'customer_list' => [$custList[0], $custList[4], $custList[6]],
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(3, $res);

        $this->assertArrayHasKey('name', $res[0]);
        $this->assertArrayHasKey('cust_id', $res[0]);
        $this->assertArrayHasKey('sites', $res[0]);
        $this->assertArrayHasKey('equipment', $res[0]);
        $this->assertArrayHasKey('notes', $res[0]);
        $this->assertArrayHasKey('contacts', $res[0]);
        $this->assertArrayHasKey('files', $res[0]);
    }
}
