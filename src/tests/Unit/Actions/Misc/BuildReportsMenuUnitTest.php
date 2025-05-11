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
                        'customer-files-report'
                    ]),
                ],
                [
                    'name' => 'Customer Equipment Report',
                    'url' => '#',
                ],
            ],
        ];

        $testObj = new BuildReportsMenu;
        $res = $testObj();

        $this->assertEquals($shouldBe, $res);
    }
}
