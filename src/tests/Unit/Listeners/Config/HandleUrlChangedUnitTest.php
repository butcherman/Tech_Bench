<?php

namespace Tests\Unit\Listeners\Config;

use App\Actions\Admin\UpdateApplicationUrl;
use App\Events\Config\UrlChangedEvent;
use App\Listeners\Config\HandleUrlChangeListener;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleUrlChangedUnitTest extends TestCase
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

        $testObj = new HandleUrlChangeListener(new UpdateApplicationUrl);
        $event = new UrlChangedEvent('newUrl.com', 'oldUrl.com');

        $testObj->handle($event);

        $newFile = file_get_contents($filePath.'/.env.testing');
        $this->assertStringContainsString('BASE_URL=newUrl.com', $newFile);
    }
}
