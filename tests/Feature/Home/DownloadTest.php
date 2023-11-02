<?php

namespace Tests\Feature\Home;

use App\Models\FileUploads;
use App\Models\User;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest_bad_filename()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png']);

        $response = $this->get(route('download', [$file->file_id, 'blah.jpb']));
        $response->assertStatus(404);
    }

    public function test_invoke_guest_bad_file_id()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png']);

        $response = $this->get(route('download', [84587451, $file->file_name]));
        $response->assertStatus(404);
    }

    public function test_invoke_guest_private_file()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png', 'public' => false]);

        $response = $this->get(route('download', [$file->file_id, $file->file_name]));
        $response->assertStatus(403);
    }

    public function test_invoke_guest_missing_file()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png', 'public' => true]);

        $response = $this->get(route('download', [$file->file_id, $file->file_name]));
        $response->assertStatus(404);
    }

    public function test_invoke_bad_filename()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png']);

        $response = $this->actingAs(User::factory()->create())->get(route('download', [$file->file_id, 'blah.jpb']));
        $response->assertStatus(404);
    }

    public function test_invoke_bad_file_id()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png']);

        $response = $this->actingAs(User::factory()->create())->get(route('download', [84587451, $file->file_name]));
        $response->assertStatus(404);
    }

    public function test_invoke_missing_file()
    {
        $file = FileUploads::factory()->create(['file_name' => 'someFile.png', 'public' => true]);

        $response = $this->actingAs(User::factory()->create())->get(route('download', [$file->file_id, $file->file_name]));
        $response->assertStatus(404);
    }
}
