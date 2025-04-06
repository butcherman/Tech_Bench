<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class AppRebootCommandTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->artisan('app:reboot')
            ->expectsConfirmation('Are you sure you want to reboot?', 'yes')
            ->assertExitCode(0);
    }

    public function test_handle_no_confirmation(): void
    {
        $this->artisan('app:reboot')
            ->expectsConfirmation('Are you sure you want to reboot?', 'np')
            ->assertExitCode(1);
    }

    public function test_handle_force(): void
    {
        $this->artisan('app:reboot --force')
            ->assertExitCode(0);
    }
}
