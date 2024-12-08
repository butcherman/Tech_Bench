<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class RunMaintenanceTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_command(): void
    {
        $this->artisan('app:maintenance --fix')->assertSuccessful();
    }

    public function test_command_fix_off(): void
    {
        $this->artisan('app:maintenance')->assertSuccessful();
    }
}
