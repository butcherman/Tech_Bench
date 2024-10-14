<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Tests\TestCase;

class FileLinkAdminTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.links.manage.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.links.manage.index'));
        $response->assertForbidden();
    }

    public function test_index_no_permission()
    {
        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('admin.links.manage.index'));
        $response->assertForbidden();
    }

    public function test_index()
    {
        config(['file-link.feature_enabled' => true]);
        FileLink::factory()->count(5)->createQuietly();

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.links.manage.index'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('admin.links.manage.show', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        $link = FileLink::factory()->createQuietly();

        config(['file-link.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        $link = FileLink::factory()->createQuietly();

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show()
    {
        $link = FileLink::factory()->createQuietly();

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertSuccessful();
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        $link = FileLink::factory()->createQuietly();

        config(['file-link.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        $link = FileLink::factory()->createQuietly();

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy()
    {
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}
