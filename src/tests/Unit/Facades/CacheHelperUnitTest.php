<?php

namespace Tests\Unit\Facades;

use App\Models\UserRole;
use App\Models\UserSettingType;
use App\Services\Misc\CacheHelper;
use Illuminate\Support\Facades\Cache;
use PragmaRX\Version\Package\Version;
use Tests\TestCase;

class CacheHelperUnitTest extends TestCase
{
    /** @var CacheHelper */
    protected $helperObj;

    public function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->helperObj = new CacheHelper;
    }

    /*
    |---------------------------------------------------------------------------
    | Clear Cache
    |---------------------------------------------------------------------------
    */
    public function test_clear_cache_key(): void
    {
        Cache::put('test_key', 'test_key');

        Cache::shouldReceive('forget')
            ->once()
            ->with('test_key');

        $this->helperObj->clearCache('test_key');
    }

    public function test_clear_cache(): void
    {
        Cache::put('test_key', 'test_key');
        Cache::put('test_two', 'test_two');

        Cache::shouldReceive('flush')
            ->once();

        $this->helperObj->clearCache();
    }

    /*
    |---------------------------------------------------------------------------
    | Password Rules
    |---------------------------------------------------------------------------
    */
    public function test_password_rules_facade_method(): void
    {
        $shouldBe = [
            'Password must be at least 6 characters',
            'Must contain an Uppercase letter',
            'Must contain a Lowercase letter',
            'Must contain a Number',
            'Must contain a Special Character',
        ];

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('password_rules', \Closure::class)
            ->andReturn($shouldBe);

        $passRules = $this->helperObj->passwordRules();

        $this->assertEquals($shouldBe, $passRules);
    }

    public function test_password_rules_default_values(): void
    {
        $shouldBe = [
            'Password must be at least 6 characters',
            'Must contain an Uppercase letter',
            'Must contain a Lowercase letter',
            'Must contain a Number',
            'Must contain a Special Character',
        ];

        $passRules = $this->helperObj->passwordRules();

        $this->assertEquals($shouldBe, $passRules);
    }

    public function test_password_rules_non_default(): void
    {
        config(['auth.passwords.settings.contains_lowercase' => false]);
        config(['auth.passwords.settings.contains_uppercase' => false]);
        config(['auth.passwords.settings.contains_special' => false]);

        $shouldBe = [
            'Password must be at least 6 characters',
            'Must contain a Number',
        ];

        $passRules = $this->helperObj->passwordRules();

        $this->assertEquals($shouldBe, $passRules);
    }

    /*
    |---------------------------------------------------------------------------
    | Application Data
    |---------------------------------------------------------------------------
    */
    public function test_app_data_facade(): void
    {
        $version = new Version;

        $shouldBe = [
            'name' => config('app.name'),
            'company_name' => config('app.company_name'),
            'logo' => config('app.logo'),
            'version' => $version->full(),
            'copyright' => $version->copyright(),
            'build' => $version->commit(),
            'build_date' => $version->build(),

            // File information
            'fileData' => [
                'maxSize' => config('filesystems.max_filesize'),
                'chunkSize' => config('filesystems.chunk_size'),
            ],

            // Inactivity timeout
            'idle_timeout' => intval(config('auth.auto_logout_timer')),
        ];

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('appData', \Closure::class)
            ->andReturn($shouldBe);

        $appData = $this->helperObj->appData();

        $this->assertEquals($shouldBe, $appData);
    }

    public function test_app_data(): void
    {
        $version = new Version;

        $shouldBe = [
            'name' => config('app.name'),
            'company_name' => config('app.company_name'),
            'logo' => config('app.logo'),
            'version' => $version->full(),
            'copyright' => $version->copyright(),
            'build' => $version->commit(),
            'build_date' => $version->build(),

            // File information
            'fileData' => [
                'maxSize' => config('filesystems.max_filesize'),
                'chunkSize' => config('filesystems.chunk_size'),
            ],

            // Inactivity timeout
            'idle_timeout' => intval(config('auth.auto_logout_timer')),
        ];

        $appData = $this->helperObj->appData();

        $this->assertEquals($shouldBe, $appData);
    }

    /*
    |---------------------------------------------------------------------------
    | User Roles
    |---------------------------------------------------------------------------
    */
    public function test_user_roles(): void
    {
        $shouldBe = UserRole::all();

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('userRoles', \Closure::class)
            ->andReturn($shouldBe);

        $roleList = $this->helperObj->userRoles();

        $this->assertEquals($shouldBe->toArray(), $roleList->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | User Settings Types
    |---------------------------------------------------------------------------
    */
    public function test_user_settings_type(): void
    {
        $shouldBe = UserSettingType::all();

        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('userSettingsType', \Closure::class)
            ->andReturn($shouldBe);

        $settingTypeList = $this->helperObj->userSettingsType();

        $this->assertEquals($shouldBe->toArray(), $settingTypeList->toArray());
    }
}