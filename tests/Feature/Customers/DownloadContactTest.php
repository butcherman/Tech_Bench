<?php

namespace Tests\Feature\Customers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;

class DownloadContactTest extends TestCase
{
    public function test_invoke_guest()
    {
        $cust = Customer::factory()->create();
        $cont = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        CustomerContactPhone::factory()->create(['cont_id' => $cont->cont_id]);

        $response = $this->get(route('customers.contacts.download', $cont->cont_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }
}
