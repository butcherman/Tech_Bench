<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;
use App\Models\UserEmailNotifications;

class UserEmailNotificationsTest extends TestCase
{
    protected $user;

    public function setup():void
    {
        Parent::setup();

        $this->user  = User::factory()->create();
    }

    /*
    *   Update Function
    */
    public function test_update_guest()
    {
        $form = UserEmailNotifications::factory()->make(['user_id' => $this->user->user_id])->toArray();

        $request = $this->put(route('email-notifications.update', $this->user->user_id), $form);
        $request->assertStatus(302);
        $request->assertRedirect(route('login.index'));
    }

    public function test_update_different_user()
    {
        $user2 = User::factory()->create();
        $form = UserEmailNotifications::factory()->make(['user_id' => $user2->user_id])->toArray();

        $request = $this->actingAs($this->user)->put(route('email-notifications.update', $user2->user_id), $form);
        $request->assertStatus(403);
    }

    public function test_update_as_admin()
    {
        $form  = UserEmailNotifications::factory()->make(['user_id' => $this->user->user_id])->toArray();

        $request = $this->actingAs(User::factory()->create(['role_id' => 2]))->put(route('email-notifications.update', $this->user->user_id), $form);
        $request->assertStatus(302);
        $request->assertSessionHas(['message' => 'Email Notifications Updated']);
    }

    public function test_update()
    {
        $form  = UserEmailNotifications::factory()->make(['user_id' => $this->user->user_id])->toArray();

        $request = $this->actingAs($this->user)->put(route('email-notifications.update', $this->user->user_id), $form);
        $request->assertStatus(302);
        $request->assertSessionHas(['message' => 'Email Notifications Updated']);
    }
}
