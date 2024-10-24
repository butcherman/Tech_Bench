<?php

namespace Tests\Feature\Report\User;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class UserContributionReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::factory()->count(20)->createQuietly();
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('reports.user.contribution'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('reports.user.contribution'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->get(route('reports.user.contribution'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $data = [
            'start_date' => Carbon::today()->subDays(30)->format('Y-d-m'),
            'end_date' => Carbon::today()->format('Y-d-m'),
            'user_list' => User::all()->map(fn ($user) => $user->username)->toArray(),
        ];

        $response = $this->put(route('reports.user.run-contribution'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $data = [
            'start_date' => Carbon::today()->subDays(30)->format('Y-d-m'),
            'end_date' => Carbon::today()->format('Y-d-m'),
            'user_list' => User::all()->map(fn ($user) => $user->username)->toArray(),
        ];

        $response = $this->ActingAs(User::factory()->createQuietly())
            ->put(route('reports.user.run-contribution'), $data);
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $data = [
            'start_date' => Carbon::today()->subDays(30)->format('Y-m-d'),
            'end_date' => Carbon::today()->format('Y-m-d'),
            'user_list' => User::all()->map(fn ($user) => $user->username)->toArray(),
        ];

        $response = $this->ActingAs(User::factory()->createQuietly(['role_id' => 2]))
            ->put(route('reports.user.run-contribution'), $data);
        $response->assertSuccessful();
    }
}
