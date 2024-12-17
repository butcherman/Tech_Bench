<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildReportsMenu;
use Tests\TestCase;

class BuildReportsMenuUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | build()
    |---------------------------------------------------------------------------
    */
    public function test_build(): void
    {
        $shouldBe = [
            'User Reports' => [
                [
                    'name' => 'User Summary Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-summary-report']
                    ),
                ],
                [
                    'name' => 'User Login Activity Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-login-activity-report']
                    ),
                ],
                [
                    'name' => 'User Contributions Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-contributions-report']
                    ),
                ],
                [
                    'name' => 'User Permissions Report',
                    'route' => route(
                        'reports.params',
                        ['users', 'user-permissions-report']
                    ),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'route' => route(
                        'reports.params',
                        ['customers', 'customer-summary-report']
                    ),
                ],
                [
                    'name' => 'Customer Files Report',
                    'route' => route(
                        'reports.params',
                        ['customers', 'customer-files-report']
                    ),
                ],
            ],
        ];

        $testObj = new BuildReportsMenu;
        $res = $testObj();

        $this->assertEquals($shouldBe, $res);
    }
}
