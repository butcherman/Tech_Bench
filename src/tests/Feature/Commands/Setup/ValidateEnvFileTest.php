<?php

namespace Tests\Feature\Commands\Setup;

use Tests\TestCase;

class ValidateEnvFileTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $env = "APP_ENV=testing\n".
               "APP_KEY=base64:jZ4t3vJLWff1TXMjQhGmEBUosQFr0Ec0qbXM2hIwgwM=\n".
               "APP_URL=https://localhost\n".
               "APP_DEBUG=true\n".
               "LOG_LEVEL=debug\n".
               "QUEUE_CONNECTION=sync\n".
               "TELESCOPE_ENABLED=false\n";

        file_put_contents(base_path('.env.testing'), $env);

    }

    /**
     * A basic feature test example.
     */
    public function test_handle(): void
    {
        $this->artisan('app:validate-env')
            ->assertExitCode(0);

        // Assert the new ENV Keys exist
        $newEnv = file_get_contents(base_path('.env.testing'));
        $matchPatterns = [
            'BASE_URL=localhost',
            'APP_URL="https://${BASE_URL}"',
            'REVERB_APP_ID=',
            'REVERB_APP_KEY=',
            'REVERB_APP_SECRET=',
            'REVERB_HOST="${BASE_URL}"',
            'VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"',
            'VITE_REVERB_HOST="${REVERB_HOST}"',
        ];

        foreach ($matchPatterns as $pattern) {
            $this->assertTrue((bool) preg_match('/^'.preg_quote($pattern, '/').'/m', $newEnv));
        }
    }
}
