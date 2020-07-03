<?php

namespace Tests\Feature\Customers;

use App\CustomerFiles;
use App\Customers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerFilesControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('customer.files.index'));
        $response->assertSuccessful();
        $response->assertJsonStructure([['file_type_id', 'description']]);
    }

    public function test_index_guest()
    {
        $response = $this->get(route('customer.files.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create()
    {
        Storage::fake(config('filesystems.paths.customers'));

        $cust = factory(Customers::class)->create();
        $file = UploadedFile::fake()->image('image.jpg');
        $data = [
            'cust_id'      => $cust->cust_id,
            'name'         => 'Test File Description',
            'file_type_id' => 1,
            'shared'       => false,
            'file'         => $file,
        ];

        $response = $this->actingAs($this->getTech())->post(route('customer.files.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_create_to_parent()
    {
        Storage::fake(config('filesystems.paths.customers'));

        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $file   = UploadedFile::fake()->image('image.jpg');
        $data   = [
            'cust_id'      => $cust->cust_id,
            'name'         => 'Test File Description',
            'file_type_id' => 1,
            'shared'       => true,
            'file'         => $file,
        ];

        $response = $this->actingAs($this->getTech())->post(route('customer.files.store'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_create_guest()
    {
        Storage::fake(config('filesystems.paths.customers'));

        $cust = factory(Customers::class)->create();
        $file = UploadedFile::fake()->image('image.jpg');
        $data = [
            'cust_id'      => $cust->cust_id,
            'name'         => 'Test File Description',
            'file_type_id' => 1,
            'shared'       => false,
            'file'         => $file,
        ];

        $response = $this->post(route('customer.files.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $cust  = factory(Customers::class)->create();
        factory(CustomerFiles::class, 5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->actingAs($this->getTech())->get(route('customer.files.show', $cust->cust_id));
        $response->assertSuccessful();
        $response->assertJsonCount(5);
    }

    public function test_show_guest()
    {
        $cust  = factory(Customers::class)->create();
        factory(CustomerFiles::class, 5)->create(['cust_id' => $cust->cust_id]);

        $response = $this->get(route('customer.files.show', $cust->cust_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $cust = factory(Customers::class)->create();
        $file = factory(CustomerFiles::class)->create(['cust_id' => $cust->cust_id]);
        $data = [
            'cust_id'      => $cust->cust_id,
            'file_type_id' => 1,
            'shared'       => false,
            'name'         => 'New File Name',
        ];

        $response = $this->actingAs($this->getTech())->put(route('customer.files.update', $file->cust_file_id), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_update_guest()
    {
        $cust = factory(Customers::class)->create();
        $file = factory(CustomerFiles::class)->create(['cust_id' => $cust->cust_id]);
        $data = [
            'cust_id'      => $cust->cust_id,
            'file_type_id' => 1,
            'shared'       => false,
            'name'         => 'New File Name',
        ];

        $response = $this->put(route('customer.files.update', $file->cust_file_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy()
    {
        $file = factory(CustomerFiles::class)->create();

        $response = $this->actingAs($this->getTech())->delete(route('customer.files.destroy', $file->cust_file_id));
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_destroy_guest()
    {
        $file = factory(CustomerFiles::class)->create();

        $response = $this->delete(route('customer.files.destroy', $file->cust_file_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
