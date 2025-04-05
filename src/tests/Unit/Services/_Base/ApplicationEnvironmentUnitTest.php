<?php

namespace Tests\Unit\Services\_Base;

use App\Services\_Base\ApplicationEnvironment;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationEnvironmentUnitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();

        $env = [
            'APP_KEY=test',
            'APP_URL=https://localhost',
            'BASE_URL=localhost',
        ];

        Storage::put('envTest/.env.testing', print_r(implode("\r\n", $env), true));
        $filePath = Storage::path('envTest');

        App::useEnvironmentPath($filePath);
    }

    /*
    |---------------------------------------------------------------------------
    | getEnvKeyValue()
    |---------------------------------------------------------------------------
    */
    public function test_get_env_key_value(): void
    {
        $shouldBe = "test\r";

        $testObj = new class extends ApplicationEnvironment
        {
            public function getTestValue($str)
            {
                return $this->getEnvKeyValue($str);
            }
        };
        $res = $testObj->getTestValue('APP_KEY');

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | writeNewEnvironmentFileWith()
    |---------------------------------------------------------------------------
    */
    public function test_write_new_environment_file_with(): void
    {
        $testObj = new class extends ApplicationEnvironment
        {
            public function getTestValue($key, $str)
            {
                return $this->writeNewEnvironmentFileWith($key, $str);
            }
        };
        $res = $testObj->getTestValue('TEST_KEY', 'testValue');

        $this->assertTrue($res);

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));
        $this->assertStringContainsString('TEST_KEY=testValue', $envFile);
    }

    /*
    |---------------------------------------------------------------------------
    | writeNewEnvironmentFileReplacing()
    |---------------------------------------------------------------------------
    */
    public function test_write_new_environment_file_replacing(): void
    {
        $testObj = new class extends ApplicationEnvironment
        {
            public function getTestValue($key, $str)
            {
                return $this->writeNewEnvironmentFileReplacing($key, $str);
            }
        };
        $res = $testObj->getTestValue('APP_KEY', 'newTestValue');

        $this->assertTrue($res);

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));
        $this->assertStringContainsString('APP_KEY=newTestValue', $envFile);
    }

    public function test_write_new_environment_file_replacing_fail(): void
    {
        $testObj = new class extends ApplicationEnvironment
        {
            public function getTestValue($key, $str)
            {
                return $this->writeNewEnvironmentFileReplacing($key, $str);
            }
        };
        $res = $testObj->getTestValue('MISSING_KEY', 'newTestValue');

        $this->assertFalse($res);

        $envFile = file_get_contents(Storage::path('envTest/.env.testing'));
        $this->assertStringNotContainsString('MISSING_KEY=newTestValue', $envFile);
    }
}
