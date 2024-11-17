<?php

namespace Tests\Feature\API;

use App\Models\User;
use Tests\TestCase;

class GetEquipmentListTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest(): void
    {
        $response = $this->get(route('equipment-list'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('equipment-list'));

        $response->assertSuccessful();
    }
}
