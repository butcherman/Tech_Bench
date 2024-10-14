<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Tests\TestCase;

class DownloadNoteTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);

        $response = $this->get(route('customers.notes.download', [
            $customer->slug,
            $note->note_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $customer = Customer::factory()->create();
        $note = CustomerNote::factory()->create([
            'cust_id' => $customer->cust_id,
        ]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('customers.notes.download', [
                $customer->slug,
                $note->note_id,
            ]));
        $response->assertSuccessful();
    }
}
