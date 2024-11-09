<?php

namespace Tests\Unit\Services\Customer;

use App\Services\Customer\CustomerAdministrationService;
use Tests\TestCase;

class CustomerAdministrationUnitTest extends TestCase
{
    public function test_update_customer_settings(): void
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'OR',
            'auto_purge' => false,
        ];

        $testObj = new CustomerAdministrationService;
        $testObj->updateCustomerSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.select_id',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.update_slug',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.default_state',
            'value' => 'OR',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.auto_purge',
        ]);
    }
}
