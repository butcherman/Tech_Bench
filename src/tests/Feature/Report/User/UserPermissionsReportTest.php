<?php

namespace Tests\Feature\Report\User;

use App\Models\User;
use Tests\TestCase;

class UserPermissionsReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::factory()->count(20)->create();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('reports.user.permissions'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('reports.user.permissions'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))
            ->get(route('reports.user.permissions'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $data = [
            'allUsers' => true,
            'disabledUsers' => true,
        ];

        $response = $this->put(route('reports.user.run-permissions'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $data = [
            'allUsers' => true,
            'disabledUsers' => true,
        ];

        $response = $this->ActingAs(User::factory()->create())
            ->put(route('reports.user.run-permissions'), $data);
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $data = [
            'allUsers' => true,
            'disabledUsers' => false,
        ];

        $response = $this->ActingAs(User::factory()->create(['role_id' => 2]))
            ->put(route('reports.user.run-permissions'), $data);
        $response->assertSuccessful();
    }

    public function test_show_disabled()
    {
        $data = [
            'allUsers' => true,
            'disabledUsers' => true,
        ];

        $response = $this->ActingAs(User::factory()->create(['role_id' => 2]))
            ->put(route('reports.user.run-permissions'), $data);
        $response->assertSuccessful();
    }

    public function test_show_some()
    {
        $data = [
            'allUsers' => false,
            'disabledUsers' => false,
            'user_list' => User::inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn($u) => $u->username)
                ->toArray(),
        ];

        $response = $this->ActingAs(User::factory()->create(['role_id' => 2]))
            ->put(route('reports.user.run-permissions'), $data);
        $response->assertSuccessful();
    }
}
