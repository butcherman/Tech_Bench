<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class HealthCheckCommandTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->artisan('health:check')->assertExitCode(0);
    }
}
