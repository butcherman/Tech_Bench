<?php

namespace Tests\Feature\Customer;

use App\Events\Customer\CustomerAlertEvent;
use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerAlertsTest extends TestCase
{
    protected $customer;

    public function setUp(): void
    {
        parent::setUp();
        $this->customer = Customer::factory()->createQuietly();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('customers.alerts.index', $this->customer->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('customers.alerts.index', $this->customer->slug));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('customers.alerts.index', $this->customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        Event::fake();

        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->post(route('customers.alerts.store', $this->customer->slug), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_store_no_permission()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->actingAs($user)
            ->post(route('customers.alerts.store', $this->customer->slug), $data);
        $response->assertStatus(403);

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_store()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = CustomerAlert::factory()->make()->only(['message', 'type']);

        $response = $this->actingAs($user)
            ->post(route('customers.alerts.store', $this->customer->slug), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.alert.created'));

        $this->assertDatabaseHas('customer_alerts', $data);

        Event::assertDispatched(CustomerAlertEvent::class);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        Event::fake();

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
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_update_no_permission()
    {
        Event::fake();

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
        $response->assertStatus(403);

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_update()
    {
        Event::fake();

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
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.alert.updated'));

        $this->assertDatabaseHas('customer_alerts', $data);

        Event::assertDispatched(CustomerAlertEvent::class);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        Event::fake();

        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->delete(route('customers.alerts.destroy', [
            $this->customer->slug,
            $alert->alert_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_destroy_no_permission()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.alerts.destroy', [
                $this->customer->slug,
                $alert->alert_id,
            ]));
        $response->assertStatus(403);

        Event::assertNotDispatched(CustomerAlertEvent::class);
    }

    public function test_destroy()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $alert = CustomerAlert::factory()
            ->createQuietly(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs($user)
            ->delete(route('customers.alerts.destroy', [
                $this->customer->slug,
                $alert->alert_id,
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.alert.destroy'));

        $this->assertDatabaseMissing('customer_alerts', $alert->toArray());

        Event::assertDispatched(CustomerAlertEvent::class);
    }
}
