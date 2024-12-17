<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RunReportTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $response = $this->put(
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
            ->put(route('reports.params', ['users', 'user-summary-report']));

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $data = [
            'allUsers' => true,
            'disabledUsers' => false,
            'user_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(
                route('reports.params', ['users', 'user-summary-report']),
                $data
            );

        $response->assertSuccessful();
    }

    public function test_invoke_invalid_report(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);

        $response = $this->actingAs($user)
            ->put(route('reports.params', ['user', 'user-summary-report']));

        $response->assertStatus(404);
    }
}
