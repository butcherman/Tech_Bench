<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerAlert;
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

    public function test_create_customer_alert(): void
    {
        $cust = Customer::factory()->create();
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $testObj = new CustomerAdministrationService;
        $testObj->createCustomerAlert(collect($data), $cust);

        $this->assertDatabaseHas('customer_alerts', $data);
    }

    public function test_update_customer_alert(): void
    {
        $cust = Customer::factory()->create();
        $alert = CustomerAlert::factory()->create(['cust_id' => $cust->cust_id]);
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $testObj = new CustomerAdministrationService;
        $testObj->updateCustomerAlert(collect($data), $alert);

        $this->assertDatabaseHas('customer_alerts', $data);
    }

    public function test_destroy_customer_alert(): void
    {
        $alert = CustomerAlert::factory()->create();

        $testObj = new CustomerAdministrationService;
        $testObj->destroyCustomerAlert($alert);

        $this->assertDatabaseMissing(
            'customer_alerts',
            $alert->only(['alert_id'])
        );
    }
}
