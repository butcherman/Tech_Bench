<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FileLinkTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('links.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled()
    {
        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.index'));
        $response->assertForbidden();
    }

    public function test_index_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.index'));
        $response->assertForbidden();
    }

    public function test_index()
    {
        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('links.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_feature_disabled()
    {
        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.create'));
        $response->assertForbidden();
    }

    public function test_create_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.create'));
        $response->assertForbidden();
    }

    public function test_create()
    {
        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->post(route('links.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled()
    {
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('links.store'), $data);
        $response->assertForbidden();
    }

    public function test_store_no_permission()
    {
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('links.store'), $data);
        $response->assertForbidden();
    }

    public function test_store()
    {
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('links.store'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('file_links', [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->get(route('links.show', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show()
    {
        $link = FileLink::factory()->create();

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('links.show', $link->link_id));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->get(route('links.edit', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_feature_disabled()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));
        $response->assertForbidden();
    }

    public function test_edit_no_permission()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));
        $response->assertForbidden();
    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->put(route('links.update', $link->link_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs($user)
            ->put(route('links.update', $link->link_id), $data);
        $response->assertForbidden();
    }

    public function test_update_no_permission()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs($user)
            ->put(route('links.update', $link->link_id), $data);
        $response->assertForbidden();
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->put(route('links.update', $link->link_id), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->delete(route('links.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));
        $response->assertForbidden();
    }

    public function test_destroy_as_admin()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('links.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));
        $response->assertStatus(302);
        $response->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}