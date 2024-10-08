<?php

namespace Tests\Feature\Equipment;

use App\Models\User;
use Tests\TestCase;

class EquipmentListTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('equipment-list'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->createQuietly())->get(route('equipment-list'));
        $response->assertSuccessful();
    }
}
