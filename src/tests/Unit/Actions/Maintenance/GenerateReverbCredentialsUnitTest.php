<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\GenerateReverbCredentials;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateReverbCredentialsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Storage::fake();

        $env = [
            'APP_KEY=test',
            'APP_URL=https://localhost',
            'BASE_URL=localhost',
            'REVERB_APP_ID=23',
            'REVERB_APP_KEY=24',
            'REVERB_APP_SECRET=25',
        ];

        Storage::put('envTest/.env.testing', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('envTest');

        App::useEnvironmentPath($filePath);

        $testObj = new GenerateReverbCredentials;
        $testObj();

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));

        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringNotContainsString('REVERB_APP_ID=23', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringNotContainsString('REVERB_APP_KEY=24', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);
        $this->assertStringNotContainsString('REVERB_APP_SECRET=25', $envFile);
    }

    public function test_invoke_missing_keys(): void
    {
        Storage::fake();

        $env = [
            'APP_KEY=test',
            'APP_URL=https://localhost',
            'BASE_URL=localhost',
        ];

        Storage::put('envTest/.env.testing', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('envTest');

        App::useEnvironmentPath($filePath);

        $testObj = new GenerateReverbCredentials;
        $testObj();

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));

        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);
    }
}
