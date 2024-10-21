<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FileLinkAdminTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.links.manage.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertForbidden();
    }

    public function test_index_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertForbidden();
    }

    public function test_index()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        FileLink::factory()
            ->count(5)
            ->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Manage/Index')
                ->has('link-list')
            );
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('admin.links.manage.show', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.show', $link->link_id));

        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.show', $link->link_id));

        $response->assertForbidden();
    }

    public function test_show()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.links.manage.show', $link->link_id));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Manage/Show')
                ->has('link')
                ->has('table-data')
                ->has('timeline')
                ->has('downloadable-files')
                ->has('uploaded-files')
            );
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->delete(
            route('admin.links.manage.destroy', $link->link_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.links.manage.destroy', $link->link_id));

        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.links.manage.destroy', $link->link_id));

        $response->assertForbidden();
    }

    public function test_destroy()
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs($actingAs)
            ->delete(route('admin.links.manage.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}
