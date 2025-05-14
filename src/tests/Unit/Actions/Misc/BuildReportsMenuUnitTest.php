<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\BuildReportsMenu;
use Tests\TestCase;

class BuildReportsMenuUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        $shouldBe = [
            'User Reports' => [
                [
                    'name' => 'User Summary Report',
                    'url' => route('reports.params', [
                        'users',
                        'user-summary-report',
                    ]),
                ],
                [
                    'name' => 'User Login Activity Report',
                    'url' => route('reports.params', [
                        'users',
                        'user-login-activity-report',
                    ]),
                ],
                [
                    'name' => 'User Contributions Report',
                    'url' => route('reports.params', [
                        'users',
                        'user-contributions-report',
                    ]),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'url' => route('reports.params', [
                        'customers',
                        'customer-summary-report',
                    ]),
                ],
                [
                    'name' => 'Customer Files Report',
                    'url' => route('reports.params', [
                        'customers',
                        'customer-files-report',
                    ]),
                ],
                [
                    'name' => 'Customer Equipment Report',
                    'url' => route('reports.params', [
                        'customers',
                        'customer-equipment-report',
                    ]),
                ],
            ],
        ];

        $testObj = new BuildReportsMenu;
        $res = $testObj();

        $this->assertEquals($shouldBe, $res);
    }
}
