<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\UpdateApplicationUrl;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class UpdateApplicationUrlUnitTest extends TestCase
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

    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $testObj = new UpdateApplicationUrl;
        $testObj->handle('newUrl.org');

        $newFile = file_get_contents(App::environmentFilePath());
        $this->assertStringContainsString('BASE_URL=newUrl.org', $newFile);
    }
}
