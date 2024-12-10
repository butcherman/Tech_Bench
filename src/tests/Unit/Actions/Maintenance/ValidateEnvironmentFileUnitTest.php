<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\ValidateEnvironmentFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidateEnvironmentFileUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Storage::fake();

        $env = ['APP_KEY=test', 'APP_URL=https://localhost'];

        Storage::put('envTest/.env.testing', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('envTest');

        App::useEnvironmentPath($filePath);

        $testObj = new ValidateEnvironmentFile;
        $testObj();

        $envFile = file_get_contents($filePath.'/.env.testing');
        $this->assertStringContainsString('BASE_URL=', $envFile);
        $this->assertStringContainsString('APP_URL="https://${BASE_URL}', $envFile);
        $this->assertStringContainsString('REVERB_APP_ID=', $envFile);
        $this->assertStringContainsString('REVERB_APP_KEY=', $envFile);
        $this->assertStringContainsString('REVERB_APP_SECRET=', $envFile);
    }
}
