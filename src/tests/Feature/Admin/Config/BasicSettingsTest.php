<?php

namespace Tests\Feature\Admin\Config;

use App\Events\Config\UrlChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BasicSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.basic-settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.basic-settings.show'));
        $response->assertForbidden();
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.basic-settings.show'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Config/Settings')
                ->has('settings')
                ->has('timezone-list')
            );
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        Event::fake();

        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'UTC',
            'max_filesize' => 123456,
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->put(route('admin.basic-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(UrlChangedEvent::class);
    }

    public function test_update_no_permission()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'UTC',
            'max_filesize' => 123456,
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.basic-settings.update'), $data);

        $response->assertForbidden();

        Event::assertNotDispatched(UrlChangedEvent::class);
    }

    public function test_update()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'America/LosAngeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.basic-settings.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.config.updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.url',
            'value' => $data['url'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);

        Event::assertDispatched(UrlChangedEvent::class);
    }

    public function test_update_url_not_changed()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'url' => str_replace('https://', '', config('app.url')),
            'timezone' => 'America/LosAngeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.basic-settings.update'), $data);
        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.config.updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);

        Event::assertNotDispatched(UrlChangedEvent::class);
    }
}
