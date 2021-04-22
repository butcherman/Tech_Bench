<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;

use App\Models\User;

class DisabledUserTest extends TestCase
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
        $response = $this->get(route('admin.disabled.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.disabled.index'));
        $response->assertStatus(403);
    }

    public function test_index_admin()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.disabled.index'));
        $response->assertSuccessful();
    }

    public function test_update_guest()
    {
        $disabled = User::factory()->create(['deleted_at' => Carbon::now()->yesterday()]);
        $response = $this->put(route('admin.disabled.update', $disabled->user_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_user()
    {
        $disabled = User::factory()->create(['deleted_at' => Carbon::now()->yesterday()]);
        $response = $this->actingAs($this->user)->put(route('admin.disabled.update', $disabled->user_id));

        $response->assertStatus(403);
    }

    public function test_update()
    {
        $disabled = User::factory()->create(['deleted_at' => Carbon::now()->yesterday()]);
        $response = $this->actingAs($this->admin)->put(route('admin.disabled.update', $disabled->user_id));

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'user_id'    => $disabled->user_id,
            'username'   => $disabled->username,
            'deleted_at' => null,
        ]);
    }
}
