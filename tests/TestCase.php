<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setup();

        //  Cleanup memory leak caused by Faker
        gc_collect_cycles();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        //  Cleanup memory leak caused by Faker
        gc_collect_cycles();
    }
}
