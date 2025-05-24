<?php

namespace Tests\Feature\FileLink;

use App\Jobs\FileLink\ProcessLinkFilesJob;
use App\Models\FileLink;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class FileLinkTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('links.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertForbidden();
    }

    public function test_index_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('FileLink/Index')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('links.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertForbidden();
    }

    public function test_create_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('links.create'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('FileLink/Create')
                    ->has('default-expire')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
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

    public function test_store_feature_disabled(): void
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

    public function test_store_no_permission(): void
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

    public function test_store(): void
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

        Bus::assertNotDispatched(ProcessLinkFilesJob::class);
    }

    public function test_store_with_file(): void
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
            ->assertExists('tmp' . DIRECTORY_SEPARATOR . 'testPhoto.png');

        Bus::assertNotDispatched(ProcessLinkFilesJob::class);
    }

    public function test_store_with_file_saved(): void
    {
        config(['file-link.feature_enabled' => true]);

        Storage::fake('fileLinks');
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $fileList = FileUpload::factory()->create()->pluck('file_id')->toArray();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $response = $this->actingAs($user)
            ->withSession(['link-file' => $fileList])
            ->post(route('links.store'), $data);

        $response->assertStatus(302);

        Bus::assertDispatched(ProcessLinkFilesJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('links.show', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled(): void
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

    public function test_show_no_permission(): void
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

    public function test_show_other_user(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()
            ->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->get(route('links.show', $link->link_id));
        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('FileLink/Show')
                    ->has('link')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    // public function test_edit_guest(): void
    // {
    //     $link = FileLink::factory()->createQuietly();

    //     $response = $this->get(route('links.edit', $link->link_id));

    //     $response->assertStatus(302)
    //         ->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_edit_feature_disabled(): void
    // {
    //     config(['file-link.feature_enabled' => false]);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);

    //     $response = $this->actingAs($user)
    //         ->get(route('links.edit', $link->link_id));

    //     $response->assertForbidden();
    // }

    // public function test_edit_no_permission(): void
    // {
    //     config(['file-link.feature_enabled' => true]);
    //     $this->changeRolePermission(4, 'Use File Links', false);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);

    //     $response = $this->actingAs($user)
    //         ->get(route('links.edit', $link->link_id));

    //     $response->assertForbidden();
    // }

    // public function test_edit_other_user(): void
    // {
    //     config(['file-link.feature_enabled' => true]);

    //     /** @var User $actingAs */
    //     $actingAs = User::factory()->createQuietly();
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);

    //     $response = $this->actingAs($actingAs)
    //         ->get(route('links.edit', $link->link_id));

    //     $response->assertForbidden();
    // }

    // public function test_edit(): void
    // {
    //     config(['file-link.feature_enabled' => true]);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);

    //     $response = $this->actingAs($user)
    //         ->get(route('links.edit', $link->link_id));

    //     $response->assertSuccessful()
    //         ->assertInertia(fn (Assert $page) => $page
    //             ->component('FileLinks/Edit')
    //             ->has('link')
    //         );
    // }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    // public function test_update_guest(): void
    // {
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);
    //     $data = [
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ];

    //     $response = $this->put(route('links.update', $link->link_id), $data);

    //     $response->assertStatus(302)
    //         ->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_update_feature_disabled(): void
    // {
    //     config(['file-link.feature_enabled' => false]);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);
    //     $data = [
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ];

    //     $response = $this->actingAs($user)
    //         ->put(route('links.update', $link->link_id), $data);

    //     $response->assertForbidden();
    // }

    // public function test_update_no_permission(): void
    // {
    //     config(['file-link.feature_enabled' => true]);
    //     $this->changeRolePermission(4, 'Use File Links', false);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);
    //     $data = [
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ];

    //     $response = $this->actingAs($user)
    //         ->put(route('links.update', $link->link_id), $data);

    //     $response->assertForbidden();
    // }

    // public function test_update_other_user(): void
    // {
    //     config(['file-link.feature_enabled' => true]);

    //     /** @var User $actingAs */
    //     $actingAs = User::factory()->createQuietly();
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);
    //     $data = [
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ];

    //     $response = $this->actingAs($actingAs)
    //         ->put(route('links.update', $link->link_id), $data);

    //     $response->assertForbidden();
    // }

    // public function test_update(): void
    // {
    //     config(['file-link.feature_enabled' => true]);

    //     /** @var User $user */
    //     $user = User::factory()->createQuietly();
    //     $link = FileLink::factory()
    //         ->createQuietly(['user_id' => $user->user_id]);
    //     $data = [
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ];

    //     $response = $this->actingAs($user)
    //         ->put(route('links.update', $link->link_id), $data);

    //     $response->assertStatus(302);

    //     $this->assertDatabaseHas('file_links', [
    //         'link_id' => $link->link_id,
    //         'link_name' => 'Test Link',
    //         'expire' => '2030-12-12',
    //         'allow_upload' => true,
    //         'instructions' => 'Here are some instructions',
    //     ]);
    // }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled(): void
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

    public function test_destroy_no_permission(): void
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

    public function test_destroy_as_admin(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($actingAs)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertSessionHas('danger', 'Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }

    public function test_destroy_other_user(): void
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

    public function test_destroy(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy', $link->link_id));

        $response->assertStatus(302)
            ->assertSessionHas('danger', 'Link Deleted');

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);
    }
}
