<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\User\EmailChangedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Show Method
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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('user.user-settings.show'));

        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $user = User::factory()->create();
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email', 'role_id']);

        $response = $this->post(route('user.user-settings.store', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store()
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

        $response = $this->actingAs($user)
            ->post(route('user.user-settings.store', $user->username), $data);
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

    public function test_store_another_user_as_admin()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email']);

        //  Pull out the old email address to verify that the notification was properly sent
        $model = new User;
        $model->email = $user->email;

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('user.user-settings.store', $user->username), $data);
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

    public function test_store_another_user()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('user.user-settings.store', $user->username), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_store_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = User::factory()->make()->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))
            ->post(route('user.user-settings.store', $user->username), $data);
        $response->assertStatus(403);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs($user)
            ->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.updated'));
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->user_id,
            'setting_type_id' => 1,
            'value' => false,
        ]);
    }

    public function test_update_another_user_as_admin()
    {
        $user = User::factory()->create();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.updated'));
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->user_id,
            'setting_type_id' => 1,
            'value' => false,
        ]);
    }

    public function test_update_another_user()
    {
        $user = User::factory()->create();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))
            ->put(route('user.user-settings.update', $user->username), $data);
        $response->assertStatus(403);
    }
}
