<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\User\EmailChangedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('user.user-settings.show'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('user.user-settings.show'));

        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email', 'role_id']);

        $response = $this->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = [
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'SomeRandomEmail@em.com',
        ];

        //  Pull out the old email address to verify that the notification was properly sent
        $model = new User;
        $model->email = $user->email;

        $response = $this->actingAs($user)->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.updated'));
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Notification::assertSentTo($model, EmailChangedNotification::class);

    }

    public function test_update_another_user_as_admin()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email']);

        //  Pull out the old email address to verify that the notification was properly sent
        $model = new User;
        $model->email = $user->email;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.updated'));
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
        Notification::assertSentTo($model, EmailChangedNotification::class);
    }

    public function test_update_another_user()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create())->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_update_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(403);
    }
}