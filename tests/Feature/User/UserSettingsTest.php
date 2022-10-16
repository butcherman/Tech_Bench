<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('settings.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('settings.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    // public function test_store_guest()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'user_id'      => $user->user_id,
    //         'settingsData' => [ 'type_id_1' => false, 'type_id_2' => true ],
    //     ];

    //     $response = $this->post(route('settings.store'), $data);
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login.index'));
    //     $this->assertGuest();
    // }

    // public function test_store()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'user_id'      => $user->user_id,
    //         'settingsData' => [ 'type_id_1' => false, 'type_id_2' => true ],
    //     ];

    //     $response = $this->actingAs($user)->post(route('settings.store'), $data);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('user.notification_updated'));
    // }

    // public function test_store_another_user_as_admin()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'user_id'      => $user->user_id,
    //         'settingsData' => [ 'type_id_1' => false, 'type_id_2' => true ],
    //     ];

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('settings.store'), $data);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('user.notification_updated'));
    // }

    // public function test_store_another_user()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'user_id'      => $user->user_id,
    //         'settingsData' => [ 'type_id_1' => false, 'type_id_2' => true ],
    //     ];

    //     $response = $this->actingAs(User::factory()->create())->post(route('settings.store'), $data);
    //     $response->assertStatus(403);
    // }

    // public function test_store_higher_user()
    // {
    //     $user = User::factory()->create(['role_id' => 1]);
    //     $data = [
    //         'user_id'      => $user->user_id,
    //         'settingsData' => [ 'type_id_1' => false, 'type_id_2' => true ],
    //     ];

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->post(route('settings.store'), $data);
    //     $response->assertStatus(403);
    // }

    // /**
    //  * Update Method
    //  */
    // public function test_update_guest()
    // {
    //     $user  = User::factory()->create();
    //     $data  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

    //     $response =$this->put(route('settings.update', $user->username), $data);
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login.index'));
    //     $this->assertGuest();
    // }

    // public function test_update()
    // {
    //     $user  = User::factory()->create();
    //     $data  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

    //     $response =$this->actingAs($user)->put(route('settings.update', $user->username), $data);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('user.account_updated'));
    //     $this->assertDatabaseHas('users', [
    //         'user_id'    => $user->user_id,
    //         'first_name' => $data['first_name'],
    //         'last_name'  => $data['last_name'],
    //         'email'      => $data['email']
    //     ]);
    // }

    // public function test_update_another_user_as_admin()
    // {
    //     $user  = User::factory()->create();
    //     $data  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

    //     $response =$this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('settings.update', $user->username), $data);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('user.account_updated'));
    //     $this->assertDatabaseHas('users', [
    //         'user_id'    => $user->user_id,
    //         'first_name' => $data['first_name'],
    //         'last_name'  => $data['last_name'],
    //         'email'      => $data['email']
    //     ]);
    // }

    // public function test_update_another_user()
    // {
    //     $user  = User::factory()->create();
    //     $data  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

    //     $response =$this->actingAs(User::factory()->create())->put(route('settings.update', $user->username), $data);
    //     $response->assertStatus(403);
    //     $this->assertDatabaseMissing('users', [
    //         'user_id'    => $user->user_id,
    //         'first_name' => $data['first_name'],
    //         'last_name'  => $data['last_name'],
    //         'email'      => $data['email']
    //     ]);
    // }

    // public function test_update_higher_user()
    // {
    //     $user  = User::factory()->create(['role_id' => 1]);
    //     $data  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

    //     $response =$this->actingAs(User::factory()->create(['role_id' => 2]))->put(route('settings.update', $user->username), $data);
    //     $response->assertStatus(403);
    // }
}
