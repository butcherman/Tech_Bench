<?php

namespace Tests\Feature\Equipment;

use Tests\TestCase;
use Database\Seeders\EquipmentSeeder;
use App\Models\User;

class GetEquipmentTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('equipment.get-all'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $this->seed(EquipmentSeeder::class);

        $response = $this->actingAs(User::factory()->create())->get(route('equipment.get-all'));
        $response->assertSuccessful();
        $response->assertJsonCount(3);
    }
}
