<?php

namespace Tests\Feature\Equip;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentTypesTest extends TestCase
{
    /*
    *   Create function
    */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.equipment.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_user()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.equipment.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.equipment.create'));
        $response->assertSuccessful();
    }
}
