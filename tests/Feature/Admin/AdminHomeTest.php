<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use App\Models\User;

class AdminHomeTest extends TestCase
{
    protected $user;

    public function setup():void
    {
        Parent::setup();

        $this->user = User::factory()->create();
    }

    //  Test visiting page without logging in
    public function test_index_guest()
    {
        $response = $this->get(route('admin.index'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    //  Test visiting page without access
    public function test_index_tech()
    {
        $response = $this->actingAs($this->user)->get(route('admin.index'));
        $response->assertStatus(403);
    }

    //  Test visiting page as administrator
    public function test_index_admin()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->get(route('admin.index'));
        $response->assertSuccessful();
    }
}
