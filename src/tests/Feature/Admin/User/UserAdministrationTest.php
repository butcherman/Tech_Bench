<?php

namespace Tests\Feature\Admin\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserAdministrationTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.user.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.index'));
        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/User/Index')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('admin.user.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.create'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/User/Create')
                    ->has('roles')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        Bus::fake();

        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $response = $this->post(route('admin.user.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Bus::assertNothingDispatched();
    }

    public function test_store_no_permission(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $response = $this->actingAs($user)
            ->post(route('admin.user.store'), $data);

        $response->assertForbidden();

        Bus::assertNothingDispatched();
    }

    public function test_store(): void
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = User::factory()
            ->make()
            ->only([
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]);

        $response = $this->actingAs($user)
            ->post(route('admin.user.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.created', [
                'user' => $data['first_name'].' '.$data['last_name'],
            ]));

        $this->assertDatabaseHas('users', $data);

        Bus::assertDispatched(SendWelcomeEmailJob::class);
        Bus::assertDispatched(CreateUserSettingsJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->get(route('admin.user.show', $user->username));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $newUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.show', $newUser->username));

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $newUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.show', $newUser->username));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/User/Show')
                    ->has('user')
                    ->has('role')
                    ->has('last-login')
                    ->has('thirty-day-count')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->get(route('admin.user.edit', $user->username));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $editUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.edit', $editUser->username));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $editUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.edit', $editUser->username));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/User/Edit')
                    ->has('roles')
                    ->has('user')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $user = User::factory()->createQuietly();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->put(route('admin.user.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $editUser = User::factory()->createQuietly();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.update', $editUser->username), $data);

        $response->assertForbidden();
    }

    public function test_update_lower_role(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $editUser = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.update', $editUser->username), $data);

        $response->assertForbidden();

        $this->assertDatabaseHas('users', $editUser->only([
            'user_id',
            'username',
            'first_name',
            'last_name',
            'email',
            'role_id',
        ]));
    }

    public function test_update(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $editUser = User::factory()->createQuietly();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.update', $editUser->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.updated', [
                'user' => $data['first_name'].' '.$data['last_name'],
            ]));

        $this->assertDatabaseHas('users', $data);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $user = User::factory()->createQuietly();

        $response = $this->delete(route('admin.user.destroy', $user->username));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $delUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.user.destroy', $delUser->username));

        $response->assertForbidden();
    }

    public function test_destroy_higher_role(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $delUser = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->delete(route('admin.user.destroy', $delUser->username));

        $response->assertStatus(302);

        $this->assertSoftDeleted(
            'users',
            $delUser->only(['user_id', 'username'])
        );
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $delUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.user.destroy', $delUser->username));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('admin.user.disabled', [
                'user' => $delUser->full_name,
            ]));

        $this->assertSoftDeleted(
            'users',
            $delUser->only(['user_id', 'username'])
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    public function test_restore_guest(): void
    {
        $user = User::factory()->createQuietly();
        $user->deleteQuietly();

        $response = $this->get(route('admin.user.restore', $user->username));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $delUser = User::factory()->createQuietly();
        $delUser->deleteQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.restore', $delUser->username));

        $response->assertForbidden();
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $delUser = User::factory()->createQuietly();
        $delUser->deleteQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.restore', $delUser->username));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.restored', [
                'user' => $delUser->full_name,
            ]));

        $this->assertDatabaseHas('users', $delUser->only([
            'user_id',
            'username',
            'first_name',
            'last_name',
        ]));
    }
}
