<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerDeletedItemsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.deleted-items.index', $customer->slug));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $customer = Customer::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.index', $customer->slug));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.index', $customer->slug));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Admin/DeletedItems')
                    ->has('customer')
                    ->has('deleted-items')
            );
    }
}
