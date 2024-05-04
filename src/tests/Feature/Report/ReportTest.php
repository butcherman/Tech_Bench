<?php

namespace Tests\Feature\Report;

use App\Models\User;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('reports.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('reports.index'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))
            ->get(route('reports.index'));
        $response->assertSuccessful();
    }
}
