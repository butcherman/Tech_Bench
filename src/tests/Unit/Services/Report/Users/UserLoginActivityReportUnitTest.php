<?php

namespace Tests\Unit\Services\Report\Users;

use App\Models\User;
use App\Services\Report\Users\UserLoginActivityReport;
use Carbon\Carbon;
use Tests\TestCase;

class UserLoginActivityReportUnitTest extends TestCase
{
    protected $testObj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testObj = new UserLoginActivityReport;
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
        $shouldBe = 'UserLoginActivityReportForm';

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
        $shouldBe = 'UserLoginActivityReport';

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
            'start_date' => ['required', 'string'],
            'end_date' => ['required', 'string'],
            'user_list' => ['required', 'array'],
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
            'start_date' => Carbon::today()->subDays(30)->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'user_list' => User::all()
                ->map(fn($user) => $user->username)
                ->toArray(),
        ];

        $res = $this->testObj->generateReportData(collect($data));

        $this->assertCount(20, $res);
    }
}
