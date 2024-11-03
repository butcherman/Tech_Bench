<?php

namespace Tests\Unit\Listeners\Config;

use App\Actions\Admin\UpdateApplicationUrl;
use App\Events\Config\UrlChangedEvent;
use App\Listeners\Config\HandleUrlChangeListener;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class HandleUrlChangedUnitTest extends TestCase
{
    protected $envData;

    public function setUp(): void
    {
        parent::setUp();

        // Make a backup of the current .env file
        $this->envData = file_get_contents(App::environmentFilePath());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Put the original .env contents back
        file_put_contents(App::environmentFilePath(), $this->envData);
    }

    public function test_handle(): void
    {
        $testObj = new HandleUrlChangeListener(new UpdateApplicationUrl);
        $event = new UrlChangedEvent('newUrl.com', 'oldUrl.com');

        $testObj->handle($event);

        $newFile = file_get_contents(App::environmentFilePath());
        $this->assertStringContainsString('BASE_URL=newUrl.com', $newFile);
    }
}
