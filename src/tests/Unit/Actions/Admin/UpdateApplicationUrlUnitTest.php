<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\UpdateApplicationUrl;
use ErrorException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateApplicationUrlUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
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

        $testObj = new UpdateApplicationUrl;
        $testObj->handle('newUrl.org');

        $newFile = file_get_contents($filePath.'/.env.testing');
        $this->assertStringContainsString('BASE_URL=newUrl.org', $newFile);
    }

    public function test_handle_missing_env_file(): void
    {
        $filePath = 'invalid/path';

        App::partialMock()
            ->shouldReceive('environmentFilePath')
            ->once()
            ->andReturn($filePath);

        $this->expectException(ErrorException::class);

        $testObj = new UpdateApplicationUrl;
        $testObj->handle('newUrl.org');
    }
}
