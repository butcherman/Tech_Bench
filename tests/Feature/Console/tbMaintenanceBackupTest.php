<?php

namespace Tests\Feature\Console;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class tbMaintenanceBackupTest extends TestCase
{
    public function test_full_backup()
    {
        Storage::fake('backups');

        $this->artisan('tb_maintenance:backup --force')
            ->expectsOutputToContain('Backup successful')
            ->assertSuccessful();
    }

    public function test_database_only()
    {
        Storage::fake('backups');

        $this->artisan('tb_maintenance:backup --databaseOnly --force')
            ->expectsOutputToContain('Backup successful')
            ->assertSuccessful();
    }

    public function test_files_only()
    {
        Storage::fake('backups');

        $this->artisan('tb_maintenance:backup --filesOnly --force')
            ->expectsOutputToContain('Backup successful')
            ->assertSuccessful();
    }
}
