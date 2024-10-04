<?php

namespace Tests\Feature\Commands\Maint;

use Tests\TestCase;

class RunGarbageCollectionTest extends TestCase
{
    public function test_handle()
    {
        $this->artisan('app:collect-garbage')->assertExitCode(0);
    }
}
