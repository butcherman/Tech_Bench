<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\ValidateEnvFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidateEnvFileUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_missing_all(): void
    {
        Storage::fake();

        $env = [
            'APP_URL=https://localhost',
        ];

        Storage::put('envTest/.env.testing', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('envTest');

        App::useEnvironmentPath($filePath);

        $testObj = new ValidateEnvFile;
        $testObj();

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));

        $this->assertStringContainsString('APP_URL="https://${BASE_URL}"', $envFile);
        $this->assertStringContainsString('BASE_URL=localhost', $envFile);
        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);
        $this->assertStringContainsString('REVERB_HOST="${BASE_URL}"', $envFile);
        $this->assertStringContainsString('VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"', $envFile);
        $this->assertStringContainsString('VITE_REVERB_HOST="${REVERB_HOST}"', $envFile);
    }
}
