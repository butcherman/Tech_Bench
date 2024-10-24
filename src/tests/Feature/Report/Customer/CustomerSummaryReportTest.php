<?php

namespace Tests\Feature\Report\Customer;

use App\Models\Customer;
use App\Models\User;
use Tests\TestCase;

class CustomerSummaryReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Customer::factory()->count(20)->create();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('reports.customer.summary'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('reports.customer.summary'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->get(route('reports.customer.summary'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $data = [
            'disabledCustomers' => false,
        ];

        $response = $this->put(route('reports.customer.run-summary'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $data = [
            'disabledCustomers' => false,
        ];

        $response = $this->ActingAs(User::factory()->createQuietly())
            ->put(route('reports.customer.run-summary'), $data);
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $data = [
            'disabledCustomers' => false,
        ];

        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->put(route('reports.customer.run-summary'), $data);
        $response->assertSuccessful();
    }
}
