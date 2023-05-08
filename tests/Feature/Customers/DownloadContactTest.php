<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\User;
use Tests\TestCase;

class DownloadContactTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        CustomerContactPhone::factory()->create(['cont_id' => $cont->cont_id, 'extension' => 123]);

        $response = $this->get(route('customers.contacts.download', $cont->cont_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        CustomerContactPhone::factory()->create(['cont_id' => $cont->cont_id, 'extension' => 123]);

        $response = $this->actingAs(User::factory()->create())->get(route('customers.contacts.download', $cont->cont_id));
        $response->assertSuccessful();
    }
}
