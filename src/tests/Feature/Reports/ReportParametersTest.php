<?php

namespace Tests\Feature\Reports;

use App\Models\User;
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
        $response = $this->get(route('reports.params', [
            'customers',
            'customer-summary-report',
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('reports.params', [
            'customers',
            'customer-summary-report',
        ]));

        $response->assertForbidden();
    }

    public function test_invoke_customer_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);

        $response = $this->actingAs($user)->get(route('reports.params', [
            'customers',
            'customer-summary-report',
        ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Report/Get')
                    ->has('group')
                    ->has('form')
                    ->has('props')
            );
    }

    public function test_invoke_user_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);

        $response = $this->actingAs($user)->get(route('reports.params', [
            'users',
            'user-summary-report',
        ]));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Report/Get')
                    ->has('group')
                    ->has('form')
                    ->has('props')
            );
    }
}
