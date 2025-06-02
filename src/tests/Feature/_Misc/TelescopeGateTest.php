<?php

namespace Tests\Feature\_Misc;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TelescopeGateTest extends TestCase
{
    public function test_gate_valid(): void
    {
        config(['telescope.enabled' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)->get('/administration/telescope/requests');

        $response->assertSuccessful();
    }

    public function test_gate_invalid_user(): void
    {
        config(['telescope.enabled' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);

        $response = $this->actingAs($user)->get('/administration/telescope');

        $response->assertForbidden();
    }

    public function test_gate_guest(): void
    {
        config(['telescope.enabled' => true]);

        $response = $this->get('/administration/telescope');

        $response->assertForbidden();
    }
}
