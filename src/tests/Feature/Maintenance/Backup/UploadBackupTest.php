<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->post(route('maint.backups.upload'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('maint.backups.upload'), $data);

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        Storage::fake('backups');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('maint.backups.upload'), $data);

        $response->assertSuccessful();

        Storage::disk('backups')->assertExists('tech-bench/randomImage.png');
    }
}
