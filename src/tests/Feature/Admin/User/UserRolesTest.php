<?php

namespace Tests\Feature\Admin\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    use WithFaker;

    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.user-roles.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Role/Index')
                    ->has('roles')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('admin.user-roles.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.create'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Role/Create')
                    ->has('permission-list')
                    ->has('base-role')
                    ->has('permission-values')
            );
    }

    public function test_create_copy_existing(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->post(route('admin.user-roles.copy'), ['role_id' => 1]);

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Role/Create')
                    ->has('permission-list')
                    ->has('base-role')
                    ->has('permission-values')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        Event::fake(FeatureChangedEvent::class);

        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->post(route('admin.user-roles.store'), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_store_no_permission(): void
    {
        Event::fake(FeatureChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.user-roles.store'), $form);

        $response->assertForbidden();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_store(): void
    {
        Event::fake(FeatureChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.user-roles.store'), $form);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user-role.created'));

        $this->assertDatabaseHas('user_roles', [
            'name' => $form['name'],
            'description' => $form['description'],
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $role = UserRole::factory()->createQuietly();

        $response = $this->get(route('admin.user-roles.show', $role->role_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $role = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.show', $role->role_id));

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $role = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.show', $role->role_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Role/Show')
                    ->has('role')
                    ->has('permission-list')
                    ->has('permission-values')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->get(
            route('admin.user-roles.edit', $testRole->role_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.edit', $testRole->role_id));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user-roles.edit', $testRole->role_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Admin/Role/Edit')
                    ->has('permission-list')
                    ->has('base-role')
                    ->has('permission-values')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        Event::fake(FeatureChangedEvent::class);

        $testRole = UserRole::factory()->createQuietly();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->put(
            route('admin.user-roles.update', $testRole->role_id),
            $form
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update_no_permission(): void
    {
        Event::fake(FeatureChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $testRole = UserRole::factory()->createQuietly();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user-roles.update', $testRole->role_id), $form);

        $response->assertForbidden();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update(): void
    {
        Event::fake(FeatureChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $testRole = UserRole::factory()->createQuietly();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user-roles.update', $testRole->role_id), $form);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user-role.updated'));

        $this->assertDatabaseHas('user_roles', [
            'role_id' => $testRole->role_id,
            'name' => $form['name'],
            'description' => $form['description'],
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    public function test_update_default_role(): void
    {
        Event::fake(FeatureChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'permissions' => [
                '1' => $this->faker->boolean(),
                '2' => $this->faker->boolean(),
                '3' => $this->faker->boolean(),
                '4' => $this->faker->boolean(),
                '5' => $this->faker->boolean(),
                '6' => $this->faker->boolean(),
                '7' => $this->faker->boolean(),
                '8' => $this->faker->boolean(),
                '9' => $this->faker->boolean(),
                '10' => $this->faker->boolean(),
                '11' => $this->faker->boolean(),
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user-roles.update', 1), $form);

        $response->assertForbidden();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->delete(
            route('admin.user-roles.destroy', $testRole->role_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.user-roles.destroy', $testRole->role_id));

        $response->assertForbidden();
    }

    public function test_destroy_default(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->delete(route('admin.user-roles.destroy', 2));

        $response->assertForbidden();
    }

    public function test_destroy_in_use(): void
    {
        Exceptions::fake();

        $this->withoutExceptionHandling();
        $this->expectException(RecordInUseException::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $testRole = UserRole::factory()->createQuietly();
        User::factory()->createQuietly(['role_id' => $testRole->role_id]);

        $response = $this->actingAs($user)
            ->delete(route('admin.user-roles.destroy', $testRole->role_id));

        $response->assertStatus(302)
            ->assertSessionHasErrors([
                'query_error' => __('admin.user-role.in-use'),
            ]);

        Exceptions::assertReported(RecordInUseException::class);
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $testRole = UserRole::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.user-roles.destroy', $testRole->role_id));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('admin.user-role.destroyed'));
    }
}
