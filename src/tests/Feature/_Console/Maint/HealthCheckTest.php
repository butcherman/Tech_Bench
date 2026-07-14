<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->artisan('health:check')->assertExitCode(0);
    }
}
