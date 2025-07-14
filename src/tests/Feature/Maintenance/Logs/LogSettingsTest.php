<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LogSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $response = $this->get(route('maint.logs.settings.show'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.settings.show'));

        $response->assertStatus(403);
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.settings.show'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/LogSettings')
                ->has('days')
                ->has('log-level')
                ->has('level-list'));
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $data = [
            'days' => '30',
            'log_level' => 'debug',
        ];

        $response = $this->put(route('maint.logs.settings.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'days' => '30',
            'log_level' => 'debug',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.logs.settings.update'), $data);

        $response->assertStatus(403);
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'days' => '120',
            'log_level' => 'critical',
        ];

        $response = $this->actingAs($user)
            ->put(route('maint.logs.settings.update'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.level',
            'value' => 'critical',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.app.level',
            'value' => 'critical',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.days',
            'value' => '120',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.app.days',
            'value' => '120',
        ]);
    }
}
