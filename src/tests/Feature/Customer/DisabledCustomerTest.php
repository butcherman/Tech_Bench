<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\User;
use App\Services\Customer\CustomerAdministrationService;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class DisabledCustomerTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('customers.disabled.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.disabled.index'));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $this->mock(CustomerAdministrationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getDisabledCustomers')->once()->andReturn(
                Customer::factory()->count(5)->create()
            );
        });

        $response = $this->actingAs($user)
            ->get(route('customers.disabled.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Customer/Admin/DisabledCustomers')
                    ->has('disabled-list')
            );
    }
}
