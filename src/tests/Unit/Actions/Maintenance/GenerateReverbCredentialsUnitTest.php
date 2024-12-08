<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\GenerateReverbCredentials;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateReverbCredentialsUnitTest extends TestCase
{
    // public function setUp(): void
    // {
    //     parent::setUp();

    //     App::fake();

    // }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_missing_keys(): void
    {
        Storage::fake();

        $env = ['APP_KEY=test', 'APP_URL=https://localhost'];

        Storage::put('env', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('env');

        App::partialMock()
            ->shouldReceive('environmentFilePath')
            ->once()
            ->andReturn($filePath);

        App::partialMock()
            ->shouldReceive('environment')
            ->once()
            ->andReturn('testing');

        $testObj = new GenerateReverbCredentials;
        $testObj();

        $envFile = file_get_contents($filePath);
        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);
    }

    public function test_invoke_default_keys(): void
    {
        Storage::fake();

        $env = [
            'APP_KEY=test',
            'APP_URL=https://localhost',
            'REVERB_APP_ID=app-id',
            'REVERB_APP_KEY=app-key',
            'REVERB_APP_SECRET=spp-secret',
        ];

        Storage::put('env', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('env');

        App::partialMock()
            ->shouldReceive('environmentFilePath')
            ->once()
            ->andReturn($filePath);

        App::partialMock()
            ->shouldReceive('environment')
            ->once()
            ->andReturn('testing');

        $testObj = new GenerateReverbCredentials;
        $testObj();

        $envFile = file_get_contents($filePath);
        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);

        // Verify default values are gone
        $this->assertStringNotContainsString('REVERB_APP_ID=app-id', $envFile);
        $this->assertStringNotContainsString('REVERB_APP_KEY=app-key', $envFile);
        $this->assertStringNotContainsString('REVERB_APP_SECRET=app-secret', $envFile);
    }
}
