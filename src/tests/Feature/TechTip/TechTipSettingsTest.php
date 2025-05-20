<?php

namespace Tests\Feature\TechTip;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TechTipSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.tech-tips.settings.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.settings.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.settings.edit'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Admin/Settings')
                    ->has('allow_comments')
                    ->has('allow_public')
                    ->has('allow_download')
                    ->has('public_link_text')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $data = [
            'allow_comments' => false,
            'allow_download' => false,
            'allow_public' => true,
            'public_link_text' => 'I am random text',
        ];

        $response = $this->put(route('admin.tech-tips.settings.edit'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'allow_comments' => false,
            'allow_download' => false,
            'allow_public' => true,
            'public_link_text' => 'I am random text',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.tech-tips.settings.edit'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'allow_comments' => false,
            'allow_download' => false,
            'allow_public' => true,
            'public_link_text' => 'I am random text',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.tech-tips.settings.edit'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_public',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_comments',
        ]);
    }
}
