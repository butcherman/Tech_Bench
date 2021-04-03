<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserEmailNotifications;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserEmailNotificationsTest extends TestCase
{
    protected $user;

    public function setup():void
    {
        Parent::setup();

        $this->user = User::factory()->create();
    }

    public function test_submit_notifications_guest()
    {
        $form = UserEmailNotifications::factory()->make();

        $response = $this->put(route('email-notifications.update', $this->user->user_id), $form->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_submit_notifications()
    {
        $form = UserEmailNotifications::factory()->make();

        $response = $this->actingAs($this->user)
                        ->put(route('email-notifications.update', $this->user->user_id), $form->toArray());
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Email Notifications Updated']);
        $this->assertDatabaseHas('user_email_notifications', $form->toArray());
    }

    public function test_submit_someone_elses_notifications()
    {
        $anotherUser = User::factory()->create();
        $form = UserEmailNotifications::factory()->make();

        $response = $this->actingAs($this->user)
                        ->put(route('email-notifications.update', $anotherUser->user_id), $form->only(['first_name', 'last_name', 'email']));
        $response->assertStatus(403);
    }
}
