<?php

namespace Tests\Feature\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FileLinkSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.links.settings.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.edit'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('FileLink/Admin/Settings')
                    ->has('feature_enabled')
                    ->has('default_link_life')
                    ->has('auto_delete_days')
                    ->has('auto_delete_override')
                    ->has('allow_custom_url'),
            );
    }

    public function test_edit_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.settings.edit'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('FileLink/Admin/Settings')
                    ->has('feature_enabled')
                    ->has('default_link_life')
                    ->has('auto_delete_days')
                    ->has('auto_delete_override')
                    ->has('allow_custom_url'),
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
            'feature_enabled' => true,
            'default_link_life' => '30',
            'auto_delete' => true,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
            'allow_custom_url' => true,
        ];

        $response = $this->put(route('admin.links.settings.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'feature_enabled' => true,
            'default_link_life' => '30',
            'auto_delete' => true,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
            'allow_custom_url' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        Event::fake();

        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'feature_enabled' => true,
            'default_link_life' => '30',
            'auto_delete' => false,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
            'allow_custom_url' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Settings Updated');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.default_link_life',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_days',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_override',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.allow_custom_url',
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    public function test_update_feature_disabled(): void
    {
        Event::fake();

        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'feature_enabled' => true,
            'default_link_life' => '30',
            'auto_delete' => false,
            'auto_delete_days' => '365',
            'auto_delete_override' => false,
            'allow_custom_url' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.links.settings.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Settings Updated');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.default_link_life',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_days',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_override',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.allow_custom_url',
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }
}
