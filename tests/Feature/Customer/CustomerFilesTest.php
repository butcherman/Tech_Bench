<?php

namespace Tests\Feature\Customer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Customers;
use App\CustomerFiles;
use App\CustomerFileTypes;
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
}