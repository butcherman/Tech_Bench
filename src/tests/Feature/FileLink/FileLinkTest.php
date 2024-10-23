<?php

namespace Tests\Feature\FileLink;

use App\Jobs\FileLink\HandleLinkFilesJob;
use App\Models\FileLink;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FileLinkTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('links.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertForbidden();
    }

    public function test_index_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertForbidden();
    }

    public function test_index()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Index')
                ->has('link-list')
            );
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('links.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertForbidden();
    }

    public function test_create_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertForbidden();
    }

    public function test_create()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Create')
                ->has('default-expire')
            );
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->post(route('links.store'), $data);

        $response->assertForbidden();
    }

    public function test_store_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->post(route('links.store'), $data);

        $response->assertForbidden();
    }

    public function test_store()
    {
        Bus::fake();

        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->post(route('links.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'File Link Created');

        $this->assertDatabaseHas('file_links', [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ]);

        Bus::assertDispatched(HandleLinkFilesJob::class);
    }

    public function test_store_with_file()
    {
        config(['file-link.feature_enabled' => true]);

        Storage::fake('fileLinks');
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.store'), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('fileLinks')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'testPhoto.png');

        Bus::assertNotDispatched(HandleLinkFilesJob::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('links.show', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));

        $response->assertForbidden();
    }

    public function test_show_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));
        $response->assertForbidden();
    }

    public function test_show_other_user()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Show')
                ->has('link')
                ->has('table-data')
                ->has('timeline')
                ->has('downloadable-files')
                ->has('uploaded-files')
            );
    }

    public function test_show()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));
        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Show')
                ->has('link')
                ->has('table-data')
                ->has('timeline')
                ->has('downloadable-files')
                ->has('uploaded-files')
            );
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('links.edit', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));

        $response->assertForbidden();
    }

    public function test_edit_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));

        $response->assertForbidden();
    }

    public function test_edit_other_user()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->get(route('links.edit', $link->link_id));

        $response->assertForbidden();
    }

    public function test_edit()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.edit', $link->link_id));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('FileLinks/Edit')
                ->has('link')
            );
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->put(route('links.update', $link->link_id), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->put(route('links.update', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_update_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->put(route('links.update', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_update_other_user()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($actingAs)
            ->put(route('links.update', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_update()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

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
        $link = FileLink::factory()->createQuietly();

        $response = $this->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertForbidden();
    }

    public function test_destroy_as_admin()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }

    public function test_destroy_other_user()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertForbidden();
    }

    public function test_destroy()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertSessionHas('danger', 'File Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}
