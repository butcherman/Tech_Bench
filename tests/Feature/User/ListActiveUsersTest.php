<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;

class ListActiveUsersTest extends TestCase
{
    protected $user;
    protected $admin;

    public function setup():void
    {
        Parent::setup();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->create(['role_id' => 2]);
    }

    public function test_index_guest()
    {
        $response = $this->get(route('admin.user.list'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.list'));
        $response->assertStatus(403);
    }

    public function test_index_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.user.list'));
        $response->assertSuccessful();
    }
}
