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
        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.manage.index'));
        $response->assertForbidden();
    }

    public function test_index_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.links.manage.index'));
        $response->assertForbidden();
    }

    public function test_index()
    {
        config(['fileLink.feature_enabled' => true]);
        FileLink::factory()->count(5)->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.manage.index'));
        $response->assertSuccessful();
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->get(route('admin.links.manage.show', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.links.manage.show', $link->link_id));
        $response->assertSuccessful();
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('admin.links.manage.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}
