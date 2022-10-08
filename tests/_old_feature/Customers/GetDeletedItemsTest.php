<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;

class GetDeletedItemsTest extends TestCase
{
    /*
    *   Invoke Method
    */
    public function test_invoke_guest()
    {
        $cust  = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create(['cust_id' => $cust->cust_id]);
        $cont  = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        $note  = CustomerNote::factory()->create(['cust_id' => $cust->cust_id]);
        $file  = CustomerFile::factory()->create(['cust_id' => $cust->cust_id]);

        $equip->delete();
        $cont->delete();
        $note->delete();
        $file->delete();

        $response = $this->get(route('customers.get-deleted', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $cust  = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create(['cust_id' => $cust->cust_id]);
        $cont  = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        $note  = CustomerNote::factory()->create(['cust_id' => $cust->cust_id]);
        $file  = CustomerFile::factory()->create(['cust_id' => $cust->cust_id]);

        $equip->delete();
        $cont->delete();
        $note->delete();
        $file->delete();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.get-deleted', $cust->cust_id));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $cust  = Customer::factory()->create();
        $equip = CustomerEquipment::factory()->create(['cust_id' => $cust->cust_id]);
        $cont  = CustomerContact::factory()->create(['cust_id' => $cust->cust_id]);
        $note  = CustomerNote::factory()->create(['cust_id' => $cust->cust_id]);
        $file  = CustomerFile::factory()->create(['cust_id' => $cust->cust_id]);

        $equip->delete();
        $cont->delete();
        $note->delete();
        $file->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.get-deleted', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJsonCount(4);
    }
}
