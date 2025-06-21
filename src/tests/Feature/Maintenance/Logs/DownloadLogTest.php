<?php

namespace Tests\Feature\Maintenance\Logs;

use App\Exceptions\Maintenance\LogFileMissingException;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadLogTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);

        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        $response = $this->get(route('maint.logs.download', [
            'Application',
            'logFile',
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('maint.logs.download', ['Application', 'logFile']));

        $response->assertForbidden();
    }

    public function test_invoke_bad_file(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(LogFileMissingException::class);

        Exceptions::fake();

        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.download', ['application', 'wrongName']));

        $response->assertStatus(404);

        Exceptions::assertReported(LogFileMissingException::class);
    }

    public function test_invoke(): void
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('maint.logs.download', ['Application', 'logFile']));

        $response->assertSuccessful()
            ->assertDownload();
    }
}
