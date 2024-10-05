<?php

namespace Tests\Unit\Actions;

use App\Actions\ReportsMenu;
use Tests\TestCase;

class ReportsMenuUnitTest extends TestCase
{
    /**
     * Verify menu is correct
     */
    public function test_get()
    {
        $obj = new ReportsMenu;
        $menu = $obj->get();

        $shouldBe = [
            'User Reports' => [
                [
                    'name' => 'User Details Report',
                    'route' => route('reports.user.details'),
                ],
                [
                    'name' => 'User Login Activity Report',
                    'route' => route('reports.user.activity'),
                ],
                [
                    'name' => 'User Contributions Report',
                    'route' => route('reports.user.contribution'),
                ],
                [
                    'name' => 'User Permissions Report',
                    'route' => route('reports.user.permissions'),
                ],
            ],
            'Customer Reports' => [
                [
                    'name' => 'Customer Summary Report',
                    'route' => route('reports.customer.summary'),
                ],
                [
                    'name' => 'Customer Files Report',
                    'route' => route('reports.customer.files'),
                ],
            ],
        ];

        $this->assertEquals($menu, $shouldBe);
    }
}
