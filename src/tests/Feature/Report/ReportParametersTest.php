<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReportParametersTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->get(
            route('reports.params', ['user', 'user-summary-report'])
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('reports.params', ['users', 'user-summary-report']));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);

        $response = $this->actingAs($user)
            ->get(route('reports.params', ['users', 'user-summary-report']));

        $response->assertSuccessful();
    }

    public function test_invoke_cust_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);

        $response = $this->actingAs($user)
            ->get(route('reports.params', [
                'customers',
                'customer-summary-report'
            ]));

        $response->assertSuccessful();
    }

    public function test_invoke_invalid_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);

        $response = $this->actingAs($user)
            ->get(route('reports.params', ['user', 'user-summary-report']));

        $response->assertStatus(404);
    }
}
