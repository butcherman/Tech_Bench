<?php

namespace Tests\Feature\Admin\Config;

use App\Events\Feature\FeatureChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.features.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.features.show'));

        $response->assertForbidden();
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.features.show'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Config/Features')
                ->has('feature-list')
            );
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        Event::fake();

        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => true,
        ];

        $response = $this->put(route('admin.features.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update_no_permission()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.features.update'), $data);

        $response->assertForbidden();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => false,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.features.update'), $data);
        $response->assertStatus(302)
            ->assertSessionHas('success', 'Feature Settings Updated');

        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.feature_enabled',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_public',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_comments',
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }
}
