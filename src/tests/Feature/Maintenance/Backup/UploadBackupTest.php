<?php

namespace Tests\Feature\Maintenance\Backup;

use App\Actions\Maintenance\ProcessUploadedBackup;
use App\Exceptions\Maintenance\BackupFileInvalidException;
use App\Models\User;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class UploadBackupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('maint.backups.upload.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('maint.backups.upload.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.backups.upload.create'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Maint/Backup/Create')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $response = $this->post(route('maint.backups.upload.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $response = $this->actingAs($user)
            ->post(route('maint.backups.upload.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $this->mock(ProcessUploadedBackup::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('__invoke')->once()->with($tusFile->id);
        });

        $response = $this->actingAs($user)
            ->post(route('maint.backups.upload.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'File Uploaded');
    }

    public function test_store_invalid_backup(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $tusFile = new TusFile(
            id: 'test-backup',
            path: 'backup.zip',
            metadata: [
                'name' => 'backup.zip',
                'purpose' => 'backup',
                'extension' => 'zip',
                'size' => 6,
            ],
        );

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $this->mock(ProcessUploadedBackup::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('__invoke')
                ->once()
                ->with($tusFile->id)
                ->andThrow(BackupFileInvalidException::class);
        });

        $response = $this->actingAs($user)
            ->post(route('maint.backups.upload.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('error');
    }
}
