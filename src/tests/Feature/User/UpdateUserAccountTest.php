<?php

namespace Tests\Feature\User;

use App\Events\User\UserEmailChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateUserAccountTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $user = User::factory()->createQuietly();
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email', 'role_id']);

        $response = $this->put(
            route('user.user-account.update', $user->username),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store(): void
    {
        Event::fake(UserEmailChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'SomeRandomEmail@em.com',
        ];

        $response = $this->actingAs($user)
            ->put(route('user.user-account.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('user.updated'));

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Event::assertDispatched(UserEmailChangedEvent::class);
    }

    public function test_store_no_email_change(): void
    {
        Event::fake(UserEmailChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => $user->email,
        ];

        //  Pull out the old email address to verify that the notification was properly sent
        $model = new User;
        $model->email = $user->email;

        $response = $this->actingAs($user)
            ->put(route('user.user-account.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('user.updated'));

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Event::assertNotDispatched(UserEmailChangedEvent::class);

    }

    public function test_store_another_user_as_admin(): void
    {
        Event::fake(UserEmailChangedEvent::class);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email']);

        //  Pull out the old email address to verify that the notification was properly sent
        $model = new User;
        $model->email = $user->email;

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-account.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('user.updated'));

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Event::assertDispatched(UserEmailChangedEvent::class);
    }

    public function test_store_another_user(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-account.update', $user->username), $data);

        $response->assertForbidden();

        $this->assertDatabaseMissing('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_store_higher_user(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 2]);

        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = User::factory()
            ->make()
            ->only(['first_name', 'last_name', 'email']);

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-account.update', $user->username), $data);

        $response->assertForbidden();
    }
}
