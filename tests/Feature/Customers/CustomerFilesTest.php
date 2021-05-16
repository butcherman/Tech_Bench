<?php

namespace Tests\Feature\Customers;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerFilesTest extends TestCase
{
    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        Storage::fake('customers');

        $file = UploadedFile::fake()->image('image.png');
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
            'file'    => $file,
        ];

        $response = $this->post(route('customers.files.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_no_permission()
    {
        Storage::fake('customers');

        $file = UploadedFile::fake()->image('image.png');
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
            'file'    => $file,
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 20)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->post(route('customers.files.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Storage::fake('customers');

        $file = UploadedFile::fake()->image('image.png');
        $data = [
            'cust_id' => Customer::factory()->create()->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
            'file'    => $file,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.files.store'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('customer_files', ['cust_id' => $data['cust_id'], 'name' => $data['name'], 'shared' => $data['shared']]);
        $this->assertDatabaseHas('file_uploads', ['disk' => 'customers', 'file_name' => 'image.png']);
        Storage::disk('customers')->assertExists($data['cust_id'].DIRECTORY_SEPARATOR.'image.png');
    }

    public function test_store_to_parent()
    {
        Storage::fake('customers');

        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $file = UploadedFile::fake()->image('image.png');
        $data = [
            'cust_id' => $cust->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => true,
            'file'    => $file,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('customers.files.store'), $data);
        $response->assertSuccessful();
        $this->assertDatabaseHas('customer_files', ['cust_id' => $cust->parent_id, 'name' => $data['name'], 'shared' => $data['shared']]);
        $this->assertDatabaseHas('file_uploads', ['disk' => 'customers', 'file_name' => 'image.png']);
        Storage::disk('customers')->assertExists($cust->parent_id.DIRECTORY_SEPARATOR.'image.png');
    }

    /*
    *   Show Method
    */
    public function test_show_guest()
    {
        $cust = Customer::factory()->create();
        CustomerFile::factory()->count(5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->get(route('customers.files.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_show()
    {
        $cust = Customer::factory()->create();
        $data = CustomerFile::factory()->count(5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs(User::factory()->create())->get(route('customers.files.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJson($data->toArray());
    }

    public function test_show_shared()
    {
        $cust = Customer::factory()->create(['parent_id' => Customer::factory()->create()->cust_id]);
        $data = CustomerFile::factory()->count(5)->create(['cust_id' => $cust->parent_id, 'shared' => true]);

        $response = $this->actingAs(User::factory()->create())->get(route('customers.files.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJson($data->toArray());
    }

    /*
    *   Update Method
    */
    public function test_update_guest()
    {
        $file = CustomerFile::factory()->create();
        $data = [
            'cust_id' => $file->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
        ];

        $response = $this->put(route('customers.files.update', $file->cust_file_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_no_permission()
    {
        $file = CustomerFile::factory()->create();
        $data = [
            'cust_id' => $file->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
        ];

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 21)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->put(route('customers.files.update', $file->cust_file_id), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $file = CustomerFile::factory()->create();
        $data = [
            'cust_id' => $file->cust_id,
            'name'    => 'This is a test file',
            'type'    => CustomerFileType::inRandomOrder()->first()->description,
            'shared'  => false,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('customers.files.update', $file->cust_file_id), $data);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'File Information Updated', 'type' => 'success']);
        $this->assertDatabaseHas('customer_files', ['cust_id' => $data['cust_id'], 'name' => $data['name'], 'shared' => $data['shared']]);
    }

    /*
    *   Destroy Method
    */
    public function test_destroy_guest()
    {
        $data = CustomerFile::factory()->create();

        $response = $this->delete(route('customers.files.destroy', $data->cust_file_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_no_permission()
    {
        $data = CustomerFile::factory()->create();

        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 22)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.files.destroy', $data->cust_file_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $data = CustomerFile::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.files.destroy', $data->cust_file_id));
        $response->assertSuccessful();
        $this->assertSoftDeleted('customer_files', $data->only(['file_id', 'cust_file_id', 'name']));
    }

    /*
    *   Restore Method
    */
    public function test_restore_guest()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->get(route('customers.files.restore', $file->cust_file_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_restore_no_permission()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->actingAs(User::factory()->create())->get(route('customers.files.restore', $file->cust_file_id));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('customers.files.restore', $file->cust_file_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Customer File restored', 'type' => 'success']);
        $this->assertDatabaseHas('customer_files', $file->only(['cust_file_id']));
    }

    /*
    *   Force Delete Method
    */
    public function test_force_delete_guest()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->delete(route('customers.files.force-delete', $file->cust_file_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_force_delete_no_permission()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->actingAs(User::factory()->create())->delete(route('customers.files.force-delete', $file->cust_file_id));
        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        Storage::fake('default');
        Storage::fake('customers');

        $file = CustomerFile::factory()->create();
        $file->delete();
        $file->save();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('customers.files.force-delete', $file->cust_file_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'File permanently deleted', 'type' => 'danger']);
        $this->assertDatabaseMissing('customer_files', $file->only(['cust_file_id']));
    }
}
