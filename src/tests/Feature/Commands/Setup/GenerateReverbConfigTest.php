<?php

namespace Tests\Feature\Commands\Setup;

use Tests\TestCase;

class GenerateReverbConfigTest extends TestCase
{
    public function test_handle(): void
    {
        preg_match('/^REVERB_APP_ID=(.*)/m', file_get_contents(base_path('.env.testing')), $curId);
        preg_match('/^REVERB_APP_KEY=(.*)/m', file_get_contents(base_path('.env.testing')), $curKey);
        preg_match('/^REVERB_APP_SECRET=(.*)/m', file_get_contents(base_path('.env.testing')), $curSecret);

        $this->artisan('reverb:generate')->assertExitCode(0);

        // Verify that the credentials have changed
        $newEnv = file_get_contents(base_path('.env.testing'));
        $this->assertFalse((bool) preg_match('/^'.$curId[0].'/m', $newEnv));
        $this->assertFalse((bool) preg_match('/^'.$curKey[0].'/m', $newEnv));
        $this->assertFalse((bool) preg_match('/^'.$curSecret[0].'/m', $newEnv));
    }
}
