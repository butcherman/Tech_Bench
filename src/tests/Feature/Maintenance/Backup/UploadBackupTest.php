<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadBackupTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->post(route('maint.backups.upload'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->post(route('maint.backups.upload'), $data);
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        Storage::fake('backups');

        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->post(route('maint.backups.upload'), $data);
        $response->assertSuccessful();

        Storage::disk('backups')->assertExists('tech-bench/randomImage.png');
    }
}
