<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected $user;

    public function setup():void
    {
        Parent::setup();

        $this->user = User::factory()->create();
    }

    public function test_index_guest()
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_tech()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));

        $response->assertSuccessful();
    }
}
