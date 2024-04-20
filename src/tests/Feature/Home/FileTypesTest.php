<?php

namespace Tests\Feature\Home;

use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\User;
use Tests\TestCase;

class FileTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.file-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.file-types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.file-types.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('file-types'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('file-types'));
        $response->assertSuccessful();
        // $response->assertJson(CustomerFileType::get()->toJson()); FIXME - check for proper json resposne
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->post(route('admin.file-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('admin.file-types.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('admin.file-types.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.file-type.created'));

        $this->assertDatabaseHas('customer_file_types', [
            'description' => $data['description'],
        ]);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $fileType = CustomerFileType::factory()->create();

        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->put(
            route('admin.file-types.update', $fileType->file_type_id),
            $data
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $fileType = CustomerFileType::factory()->create();

        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(
                route('admin.file-types.update', $fileType->file_type_id),
                $data
            );
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $fileType = CustomerFileType::factory()->create();

        $data = [
            'description' => 'New Test Description',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(
                route('admin.file-types.update', $fileType->file_type_id),
                $data
            );
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.file-type.updated'));

        $this->assertDatabaseHas('customer_file_types', [
            'file_type_id' => $fileType->file_type_id,
            'description' => $data['description'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->delete(
            route('admin.file-types.destroy', $fileType->file_type_id)
        );
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(
                route('admin.file-types.destroy', $fileType->file_type_id)
            );
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('admin.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('admin.file-type.destroyed'));

        $this->assertDatabaseMissing('customer_file_types', $fileType->only([
            'file_type_id',
        ]));
    }

    public function test_destroy_in_use()
    {
        $fileType = CustomerFileType::factory()->create();
        CustomerFile::factory()->create([
            'file_type_id' => $fileType->file_type_id,
        ]);
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('admin.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(302);
        $response->assertSessionHasErrors('query_error');

        $this->assertDatabaseHas('customer_file_types', $fileType->only([
            'file_type_id',
        ]));
    }
}
