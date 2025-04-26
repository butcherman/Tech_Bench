<?php

namespace Tests\Feature\Customer;

use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Models\User;
use Tests\TestCase;

class CustomerAlertTest extends TestCase
{
    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->createQuietly();
    }

    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(
            route('customers.alerts.index', $this->customer->slug)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.alerts.index', $this->customer->slug));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('customers.alerts.index', $this->customer->slug));

        $response->assertSuccessful();
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->post(
            route('customers.alerts.store', $this->customer->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->actingAs($user)
            ->post(
                route('customers.alerts.store', $this->customer->slug),
                $data
            );

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->actingAs($user)
            ->post(
                route('customers.alerts.store', $this->customer->slug),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.alert.created'));

        $this->assertDatabaseHas('customer_alerts', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->put(route('customers.alerts.update', [
            $this->customer->slug,
            $alert->alert_id,
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.alerts.update', [
                $this->customer->slug,
                $alert->alert_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.alerts.update', [
                $this->customer->slug,
                $alert->alert_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.alert.updated'));

        $this->assertDatabaseHas('customer_alerts', $data);
    }

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $alert = CustomerAlert::factory()
            ->createQuietly();
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.alerts.update', [
                $this->customer->slug,
                $alert->alert_id,
            ]), $data);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('customer_alerts', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->delete(route('customers.alerts.destroy', [
            $this->customer->slug,
            $alert->alert_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.alerts.destroy', [
                $this->customer->slug,
                $alert->alert_id,
            ]));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.alerts.destroy', [
                $this->customer->slug,
                $alert->alert_id,
            ]));
        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.alert.destroy'));

        $this->assertDatabaseMissing('customer_alerts', $alert->toArray());
    }
}
