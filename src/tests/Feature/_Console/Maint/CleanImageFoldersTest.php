<?php

namespace Tests\Feature\_Console\Maint;

use App\Actions\Maintenance\CleanImageFolders;
use Mockery\MockInterface;
use Tests\TestCase;

class CleanImageFoldersTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $this->mock(CleanImageFolders::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods();
            $mock->shouldReceive('__invoke')->with(true)->andReturn(true);
        });

        $this->artisan('app:cleanup-image-folders', ['--fix' => true])
            ->assertExitCode(0);
    }
}
