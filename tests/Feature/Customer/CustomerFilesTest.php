<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Customers;
use App\CustomerFiles;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CustomerFilesTest extends TestCase
{
    public $cust, $file;

    //  populate a customer and file into the database
    public function setUp(): void
    {
        Parent::setup();

        $this->cust = factory(Customers::class)->create();
        $this->file = factory(CustomerFiles::class, 5)->create([
            'cust_id' => $this->cust->cust_id
        ]);
    }

    //  test getting files as guest
    public function test_get_files_as_guest()
    {
        $response = $this->get(route('customer.files.show', $this->cust->cust_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  test getting files as tech
    public function test_get_files()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('customer.files.show', $this->cust->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'file_id','cust_file_id', 'files', 'customer_file_types', 'user']]);
    }

    //  test getting files as tech when the parent has files
    public function test_get_files_from_parent()
    {
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $user = $this->getTech();
        factory(CustomerFiles::class, 5)->create([
            'cust_id' => $this->cust->cust_id,
            'shared'  => 1,
        ]);


        $response = $this->actingAs($user)->get(route('customer.files.show', $child->cust_id));

        $response->assertSuccessful();
        $response->assertJsonStructure([['name', 'file_id', 'cust_file_id', 'files', 'customer_file_types', 'user']]);
        $response->assertJsonCount(5);
    }

    //  Test add file as guest
    public function test_add_file_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name'    => 'Test File Description',
            'type'    => $this->file[0]->file_type_id
        ];

        $response = $this->post(route('customer.files.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test add file as tech
    public function test_add_file()
    {
        Storage::fake(config('filesystems.paths.customer'));
        $user = $this->getTech();
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name'    => 'Test File Description',
            'type'    => $this->file[0]->file_type_id,
            'file'    => $file = UploadedFile::fake()->image(Str::random(5).'.jpg')
        ];

        $response = $this->actingAs($user)->post(route('customer.files.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['status' => true, 'done' => 100]);
    }

    //  Test add file as tech to child
    public function test_add_file_to_child()
    {
        Storage::fake(config('filesystems.paths.customer'));
        $user = $this->getTech();
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $data = [
            'cust_id' => $child->cust_id,
            'name'    => 'Test File Description',
            'type'    => $this->file[0]->file_type_id,
            'shared'  => 'true',
            'file'    => $file = UploadedFile::fake()->image(Str::random(5) . '.jpg')
        ];

        $response = $this->actingAs($user)->post(route('customer.files.store'), $data);

        $response->assertSuccessful();
        $response->assertJson(['status' => true, 'done' => 100]);
    }

    //  Test deleting file as guest
    public function test_delete_file_as_guest()
    {
        $response = $this->delete(route('customer.files.destroy', $this->file[2]->cust_file_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test deleting a file as tech
    public function test_delete_file()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->delete(route('customer.files.destroy', $this->file[1]->cust_file_id));

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test update a file's information as guest
    public function test_update_file_as_guest()
    {
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name' => 'This is a new name',
            'type' => $this->file[1]->file_type_id,
        ];

        $response = $this->put(route('customer.files.update', $this->file[0]->cust_file_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test update a file's information
    public function test_update_file()
    {
        $user = $this->getTech();
        $data = [
            'cust_id' => $this->cust->cust_id,
            'name' => 'This is a new name',
            'type' => $this->file[1]->file_type_id,
        ];

        $response = $this->actingAs($user)->put(route('customer.files.update', $this->file[0]->cust_file_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Test update a file's information by linking it to the parent
    public function test_update_file_link_to_parent()
    {
        $user = $this->getTech();
        $child = factory(Customers::class)->create([
            'parent_id' => $this->cust->cust_id,
        ]);
        $file = factory(CustomerFiles::class)->create([
            'cust_id' => $child->cust_id
        ]);


        $data = [
            'cust_id' => $child->cust_id,
            'name'    => 'This is a new name',
            'type'    => $this->file[1]->file_type_id,
            'shared'  => 1,
        ];

        $response = $this->actingAs($user)->put(route('customer.files.update', $file->cust_file_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);

        //  Verify that the file now belongs to the parent
        $verify = [
            'cust_file_id' => $file->cust_file_id,
            'cust_id' => $this->cust->cust_id,
            'name'    => 'This is a new name',
            'shared'  => 1,
        ];
        $this->assertDatabaseHas('customer_files', $verify);
    }
}
