<?php

namespace Tests\Feature\Commands\Setup;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GenerateReverbConfigTest extends TestCase
{
    /**
     * Create a new .env file for testing purposes
     */
    public function setUp(): void
    {
        parent::setUp();

        $env = "APP_ENV=testing\n".
               "APP_KEY=base64:jZ4t3vJLWff1TXMjQhGmEBUosQFr0Ec0qbXM2hIwgwM=\n".
               "APP_URL=https://localhost\n".
               "APP_DEBUG=true\n".
               "LOG_LEVEL=debug\n".
               "QUEUE_CONNECTION=sync\n".
               "TELESCOPE_ENABLED=false\n".
               "REVERB_APP_KEY=app-key\n".
               "REVERB_APP_SECRET=app-secret\n".
               "REVERB_APP_ID=app-id\n";

        File::copy(base_path('.env'), base_path('.env.tmp'));
        file_put_contents(base_path('.env'), $env);
    }

    /**
     * Put the original .env file back
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $newEnv = file_get_contents(base_path('.env.tmp'));
        file_put_contents(base_path('.env'), $newEnv);
        File::delete(base_path('.env.tmp'));
    }

    public function test_handle(): void
    {
        preg_match('/^REVERB_APP_ID=(.*)/m', file_get_contents(base_path('.env')), $curId);
        preg_match('/^REVERB_APP_KEY=(.*)/m', file_get_contents(base_path('.env')), $curKey);
        preg_match('/^REVERB_APP_SECRET=(.*)/m', file_get_contents(base_path('.env')), $curSecret);

        $this->artisan('reverb:generate')->assertExitCode(0);

        // Verify that the credentials have changed
        $newEnv = file_get_contents(base_path('.env'));
        $this->assertFalse((bool) preg_match('/^'.$curId[0].'/m', $newEnv));
        $this->assertFalse((bool) preg_match('/^'.$curKey[0].'/m', $newEnv));
        $this->assertFalse((bool) preg_match('/^'.$curSecret[0].'/m', $newEnv));
    }
}
