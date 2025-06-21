<?php

namespace Tests\Feature\_Console\Maint;

use App\Actions\Maintenance\GenerateReverbCredentials;
use Mockery\MockInterface;
use Tests\TestCase;

class GenerateReverbCredentialsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->mock(GenerateReverbCredentials::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('__invoke')->andReturn(true);
        });

        $this->artisan('reverb:generate')
            ->assertExitCode(0);
    }
}
