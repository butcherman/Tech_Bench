<?php

namespace Tests\Feature\Admin\Config;

use App\Events\Feature\FeatureChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.features.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.features.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.features.edit'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Admin/Config/Features')
                    ->has('feature-list')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        Event::fake();

        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => true,
            'customer_workbooks' => true,
        ];

        $response = $this->put(route('admin.features.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update_no_permission(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => false,
            'customer_workbooks' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.features.update'), $data);

        $response->assertForbidden();

        Event::assertNotDispatched(FeatureChangedEvent::class);
    }

    public function test_update(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => false,
            'customer_workbooks' => true,
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
