<?php

namespace Tests\Feature\_Console\Maint;

use App\Models\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AppMaintenanceTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle_no_problems(): void
    {
        $this->artisan('app:maintenance')->assertExitCode(0);
    }

    public function test_handle_user_errors(): void
    {
        DB::table('users')->insert([
            'user_id' => 2,
            'role_id' => 2,
            'username' => 'testUser',
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'test@noem.com',
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Users missing User Settings Data')
            ->assertExitCode(0);

        $this->assertDatabaseHas('user_settings', ['user_id' => 2]);
    }

    public function test_handle_empty_folders(): void
    {
        Storage::fake('local');
        Storage::disk('local')->makeDirectory('empty_directory');
        Storage::disk('local')->makeDirectory('not_empty');
        Storage::disk('local')->put('not_empty/test.txt', 'test file');

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('The following directories are empty and can be deleted')
            ->assertExitCode(0);

        Storage::assertMissing('empty_directory');
        Storage::assertExists('not_empty');
    }

    public function test_handle_missing_files(): void
    {
        Storage::fake('local');
        Storage::disk('local')->makeDirectory('test_one');
        Storage::disk('local')->put('test_one/valid.txt', 'valid file');

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => 0,
        ]);
        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
            'file_size' => 0,
            'public' => 0,
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files missing from filesystem.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('file_uploads', [
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
        ]);
    }

    public function test_handle_orphaned_files(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('test_one');
        Storage::put('test_one/test.txt', 'test file');
        Storage::put('test_one/valid.txt', 'valid file');

        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files without a database entry')
            ->assertExitCode(0);

        Storage::assertMissing('test_one/test.txt');
        Storage::assertExists('test_one/valid.txt');
    }

    public function test_all_file_maintenance_all_together(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_two');
        Storage::put('test_one/valid.txt', 'valid file');
        Storage::put('test_two/test.txt', 'test file');
        Storage::put('test_two/valid.txt', 'valid file');

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_two',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files missing from filesystem.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('file_uploads', [
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
        ]);

        Storage::assertMissing('test_two/test.txt');
        Storage::assertExists('test_one/valid.txt');
        Storage::assertExists('test_two/valid.txt');
    }
}
