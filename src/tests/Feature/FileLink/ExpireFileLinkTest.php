<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ExpireFileLinkTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('links.expire', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('links.expire', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke_different_user()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('links.expire', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.expire', $link->link_id));
        $response->assertStatus(403);
    }

    public function test_invoke_as_admin()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->get(route('links.expire', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Link Expired');

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'expire' => Carbon::yesterday(),
        ]);
    }

    public function test_invoke()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->get(route('links.expire', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Link Expired');

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'expire' => Carbon::yesterday(),
        ]);
    }
}
