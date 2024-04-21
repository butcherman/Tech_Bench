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
        $this->customer = Customer::factory()->create();
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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.alerts.index', $this->customer->slug));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.alerts.index', $this->customer->slug));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = CustomerAlert::factory()->make()->toArray();

        $response = $this->post(route('customers.alerts.store', $this->customer->slug), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = CustomerAlert::factory()->make()->toArray();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.alerts.store', $this->customer->slug), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Event::fake();

        $data = CustomerAlert::factory()->make()->toArray();
        $data['cust_id'] = $this->customer->cust_id;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
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
        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);
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
    }

    public function test_update_no_permission()
    {
        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.alerts.update', [
                $this->customer->slug,
                $alert->alert_id,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        Event::fake();

        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);
        $data = [
            'message' => 'updated message',
            'type' => 'success',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
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
        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);

        $response = $this->delete(route('customers.alerts.destroy', [$this->customer->slug, $alert->alert_id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.alerts.destroy', [$this->customer->slug, $alert->alert_id]));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        Event::fake();

        $alert = CustomerAlert::factory()->create(['cust_id' => $this->customer->cust_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.alerts.destroy', [$this->customer->slug, $alert->alert_id]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.alert.destroy'));

        $this->assertDatabaseMissing('customer_alerts', $alert->toArray());

        Event::assertDispatched(CustomerAlertEvent::class);
    }
}
