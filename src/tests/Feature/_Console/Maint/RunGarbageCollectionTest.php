<?php

namespace Tests\Feature\_Console\Maint;

use Tests\TestCase;

class RunGarbageCollectionTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_command(): void
    {
        $this->artisan('app:collect-garbage')->assertSuccessful();
    }
}
