<?php

namespace Tests\Feature\_Misc;

use App\Models\User;
use Tests\TestCase;

class HorizonGateTest extends TestCase
{
    public function test_gate_valid(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)->get('/administration/horizon');

        $response->assertSuccessful();
    }

    public function test_gate_invalid_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);

        $response = $this->actingAs($user)->get('/administration/horizon');

        $response->assertForbidden();
    }

    public function test_gate_guest(): void
    {
        $response = $this->get('/administration/horizon');

        $response->assertForbidden();
    }
}
