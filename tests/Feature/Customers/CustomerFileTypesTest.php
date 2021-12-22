<?php

namespace Tests\Feature\Customers;

use Tests\TestCase;

use App\Models\User;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;

class CustomerFileTypesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.cust.file-types.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.cust.file-types.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.cust.file-types.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'description' => 'New Type Description',
        ];

        $response = $this->post(route('admin.cust.file-types.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
        $this->assertDatabaseMissing('customer_file_types', ['description' => $data['description']]);
    }

    public function test_store_no_permission()
    {
        $data = [
            'description' => 'New Type Description',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.cust.file-types.store'), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('customer_file_types', ['description' => $data['description']]);
    }

    public function test_store()
    {
        $data = [
            'description' => 'New Type Description',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.cust.file-types.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_file_types', ['description' => $data['description']]);
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $fileType = CustomerFileType::Factory()->create();
        $data     = [
            'description' => 'Updated Name',
        ];

        $response = $this->put(route('admin.cust.file-types.update', $fileType->file_type_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
        $this->assertDatabaseMIssing('customer_file_types', ['description' => $data['description']]);
    }

    public function test_update_no_permission()
    {
        $fileType = CustomerFileType::Factory()->create();
        $data     = [
            'description' => 'Updated Name',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.cust.file-types.update', $fileType->file_type_id), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMIssing('customer_file_types', ['description' => $data['description']]);
    }

    public function test_update()
    {
        $fileType = CustomerFileType::Factory()->create();
        $data     = [
            'description' => 'Updated Name',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.cust.file-types.update', $fileType->file_type_id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('customer_file_types', ['description' => $data['description']]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->delete(route('admin.cust.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
        $this->assertDatabaseHas('customer_file_types', $fileType->toArray());
    }

    public function test_destroy_no_permission()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.cust.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('customer_file_types', $fileType->toArray());
    }

    public function test_destroy_in_use()
    {
        $fileType = CustomerFileType::factory()->create();
        CustomerFile::factory()->create(['file_type_id' => $fileType->file_type_id]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.cust.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Unable to delete.  This File Type is in use by some customers.',
            'type'    => 'danger',
        ]);
        $this->assertDatabaseHas('customer_file_types', $fileType->toArray());
    }

    public function test_destroy()
    {
        $fileType = CustomerFileType::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.cust.file-types.destroy', $fileType->file_type_id));
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'File Type Deleted Successfully',
            'type'    => 'success',
        ]);
        $this->assertDatabaseMissing('customer_file_types', $fileType->toArray());
    }
}
