<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Services\Customer\CustomerAlertService;
use Tests\TestCase;

class CustomerAlertServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCustomerAlert()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer_alert(): void
    {
        $customer = Customer::factory()->create();
        $data = [
            'message' => 'Test Alert',
            'type' => 'danger',
        ];

        $testObj = new CustomerAlertService;
        $testObj->createCustomerAlert(collect($data), $customer);

        $this->assertDatabaseHas('customer_alerts', [
            'cust_id' => $customer->cust_id,
            'message' => $data['message'],
            'type' => $data['type'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerAlert()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_alert(): void
    {
        $customer = Customer::factory()->create();
        $alert = CustomerAlert::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'message' => 'Test Alert',
            'type' => 'danger',
        ];

        $testObj = new CustomerAlertService;
        $testObj->updateCustomerAlert(collect($data), $alert);

        $this->assertDatabaseHas('customer_alerts', [
            'alert_id' => $alert->alert_id,
            'cust_id' => $customer->cust_id,
            'message' => $data['message'],
            'type' => $data['type'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerAlert()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_alert(): void
    {
        $customer = Customer::factory()->create();
        $alert = CustomerAlert::factory()
            ->create(['cust_id' => $customer->cust_id]);

        $testObj = new CustomerAlertService;
        $testObj->destroyCustomerAlert($alert);

        $this->assertDatabaseMissing('customer_alerts', [
            'alert_id' => $alert->alert_id,
            'cust_id' => $customer->cust_id,
        ]);
    }
}
