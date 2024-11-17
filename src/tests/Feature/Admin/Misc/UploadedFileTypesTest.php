<?php

namespace Tests\Feature\Admin\Misc;

use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UploadedFileTypesTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.file-types.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.file-types.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.file-types.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/FileType/Index')
                ->has('file-types')
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
            'description' => 'New Test Description',
        ];

        $response = $this->post(route('admin.file-types.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.file-types.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.file-types.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.file-type.created'));

        $this->assertDatabaseHas('customer_file_types', [
            'description' => $data['description'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $fileType = CustomerFileType::factory()->create();
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->put(
            route('admin.file-types.update', $fileType->file_type_id),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $fileType = CustomerFileType::factory()->create();
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs($user)->put(
            route('admin.file-types.update', $fileType->file_type_id),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $fileType = CustomerFileType::factory()->create();
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs($user)->put(
            route('admin.file-types.update', $fileType->file_type_id),
            $data
        );

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.file-type.updated'));

        $this->assertDatabaseHas('customer_file_types', [
            'file_type_id' => $fileType->file_type_id,
            'description' => $data['description'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->delete(
            route('admin.file-types.destroy', $fileType->file_type_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs($user)->delete(
            route('admin.file-types.destroy', $fileType->file_type_id)
        );

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('admin.file-types.destroy', $fileType->file_type_id));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('admin.file-type.destroyed'));

        $this->assertDatabaseMissing('customer_file_types', $fileType->only([
            'file_type_id',
        ]));
    }

    public function test_destroy_in_use(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $fileType = CustomerFileType::factory()->create();

        CustomerFile::factory()->create([
            'file_type_id' => $fileType->file_type_id,
        ]);
        $response = $this->actingAs($user)
            ->delete(route('admin.file-types.destroy', $fileType->file_type_id));

        $response->assertStatus(302)
            ->assertSessionHasErrors('query_error');

        $this->assertDatabaseHas('customer_file_types', $fileType->only([
            'file_type_id',
        ]));
    }
}
