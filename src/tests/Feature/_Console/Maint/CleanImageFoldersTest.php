<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class CleanImageFoldersTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_command(): void
    {
        $this->artisan('app:cleanup-image-folders')->assertSuccessful();
    }
}
