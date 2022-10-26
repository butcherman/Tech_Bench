<?php

namespace Tests\Feature\Admin\Logs;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DownloadLogTest extends TestCase
{
    /**
    * Invoke Method
    */
   public function test_invoke_guest()
   {
       $file = UploadedFile::fake()->create('logfile.log', 100);
       Storage::fake('logs');
       Storage::disk('logs')->putFileAs('Application', $file, 'logfile.log');

       $response = $this->get(route('admin.logs.download', ['Application', 'logfile']));
       $response->assertStatus(302);
       $response->assertRedirect(route('login.index'));
       $this->assertGuest();
   }

   public function test_invoke_no_permission()
   {
       $file = UploadedFile::fake()->create('logfile.log', 100);
       Storage::fake('logs');
       Storage::disk('logs')->putFileAs('Application', $file, 'logfile.log');

       $response = $this->actingAs(User::factory()->create())->get(route('admin.logs.download', ['Application', 'logfile']));
       $response->assertForbidden();
   }

   public function test_invoke_bad_file()
   {
       $file = UploadedFile::fake()->create('logfile.log', 100);
       Storage::fake('logs');
       Storage::disk('logs')->putFileAs('Application', $file, 'logfile.log');

       $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.download', ['Application', 'wrongName']));
       $response->assertStatus(404);
   }

   public function test_invoke()
   {
       $file = UploadedFile::fake()->create('logfile.log', 100);
       Storage::fake('logs');
       Storage::disk('logs')->putFileAs('Application', $file, 'logfile.log');

       $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logs.download', ['Application', 'logfile']));
       $response->assertSuccessful();
       $response->assertDownload();
   }
}
