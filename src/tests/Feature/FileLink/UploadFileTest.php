<?php

namespace Tests\Feature\FileLink;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadFileTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('fileLinks');
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('links.upload'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_feature_disabled()
    {
        Storage::fake('fileLinks');
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['file-link.feature_enabled' => false]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('links.upload'), $data);
        $response->assertForbidden();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('fileLinks');
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('links.upload'), $data);
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        Storage::fake('fileLinks');
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['file-link.feature_enabled' => true]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('links.upload'), $data);
        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('fileLinks')->assertExists('tmp'.DIRECTORY_SEPARATOR.'testPhoto.png');
    }
}
