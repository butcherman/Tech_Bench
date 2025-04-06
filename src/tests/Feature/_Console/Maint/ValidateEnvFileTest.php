<?php

namespace Tests\Feature\_Console\Maint;

use App\Actions\Maintenance\ValidateEnvFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ValidateEnvFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->mock(ValidateEnvFile::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('__invoke')->andReturn(true);
        });

        $this->artisan('app:validate-env')
            ->assertExitCode(0);
    }
}
