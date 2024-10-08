<?php

namespace Tests\Feature\Maintenance;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadLogTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('daily', $file, 'logFile.log');

        $response = $this->get(route('maint.logs.download', [
            'Application',
            'logFile',
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('maint.logs.download', ['daily', 'logFile']));
        $response->assertForbidden();
    }

    public function test_invoke_bad_file()
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.logs.download', ['daily', 'wrongName']));
        $response->assertStatus(404);
    }

    public function test_invoke()
    {
        $file = UploadedFile::fake()->create('logFile.log', 100);
        Storage::fake('logs');
        Storage::disk('logs')->putFileAs('Application', $file, 'logFile.log');

        $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
            ->get(route('maint.logs.download', ['Application', 'logFile']));
        $response->assertSuccessful();
        $response->assertDownload();
    }
}
