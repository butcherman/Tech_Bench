<?php

namespace Tests\Unit\Services\Report\Users;

use App\Models\User;
use App\Services\Report\Users\UserSummaryReport;
use Tests\TestCase;

class UserSummaryReportUnitTest extends TestCase
{
    protected $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObj = new UserSummaryReport;
    }

    /*
    |---------------------------------------------------------------------------
    | getReportGroup()
    |---------------------------------------------------------------------------
    */
    public function test_get_report_group(): void
    {
        $shouldBe = 'Users';

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
        $shouldBe = 'UserSummaryReportForm';

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
            'user-list' => User::all()
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
        $shouldBe = 'UserSummaryReport';

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
            'all_users' => ['required', 'boolean'],
            'disabled_users' => ['required', 'boolean'],
            'user_list' => ['array'],
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
        User::factory()->count(19)->createQuietly();


        $data = [
            'all_users' => true,
            'disabled_users' => true,
            'user_list' => [],
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(20, $res);
        $this->assertArrayHasKey('user_id', $res[0]);
        $this->assertArrayHasKey('username', $res[0]);
        $this->assertArrayHasKey('full_name', $res[0]);
        $this->assertArrayHasKey('email', $res[0]);
        $this->assertArrayHasKey('role', $res[0]);
        $this->assertArrayHasKey('created_at', $res[0]);
        $this->assertArrayHasKey('updated_at', $res[0]);
        $this->assertArrayHasKey('deleted_at', $res[0]);
        $this->assertArrayHasKey('password_expires', $res[0]);
        $this->assertArrayHasKey('last_login', $res[0]);
    }

    public function test_generate_report_data_some_users(): void
    {
        User::factory()->count(19)->createQuietly();

        $data = [
            'all_users' => false,
            'disabled_users' => false,
            'user_list' => User::inRandomOrder()
                ->limit(10)
                ->get()
                ->pluck('username')
                ->toArray(),
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(10, $res);
        $this->assertArrayHasKey('user_id', $res[0]);
        $this->assertArrayHasKey('username', $res[0]);
        $this->assertArrayHasKey('full_name', $res[0]);
        $this->assertArrayHasKey('email', $res[0]);
        $this->assertArrayHasKey('role', $res[0]);
        $this->assertArrayHasKey('created_at', $res[0]);
        $this->assertArrayHasKey('updated_at', $res[0]);
        $this->assertArrayHasKey('deleted_at', $res[0]);
        $this->assertArrayHasKey('password_expires', $res[0]);
        $this->assertArrayHasKey('last_login', $res[0]);
    }
}
