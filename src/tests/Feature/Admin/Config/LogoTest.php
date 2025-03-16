<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LogoTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.logo.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.logo.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.logo.edit'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Config/Logo'));
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('admin.logo.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.logo.update'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.logo.update'), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}
