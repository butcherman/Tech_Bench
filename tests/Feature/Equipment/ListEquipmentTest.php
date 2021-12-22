<?php

namespace Tests\Feature\Equipment;

use App\Models\User;
use Database\Seeders\EquipmentSeeder;
use Tests\TestCase;

class ListEquipmentTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('list-equipment'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $this->seed(EquipmentSeeder::class);

        $response = $this->actingAs(User::factory()->create())->get(route('list-equipment'));
        $response->assertJsonCount(3);
    }
}
