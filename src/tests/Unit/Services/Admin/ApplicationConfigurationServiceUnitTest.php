<?php

namespace Tests\Unit\Services\Admin;

use App\Models\AppSettings;
use App\Services\Admin\ApplicationConfigurationService;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApplicationConfigurationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getBasicSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_basic_settings(): void
    {
        $shouldBe = [
            'url' => preg_replace('(^https?://)', '', config('app.url')),
            'company_name' => config('app.company_name'),
            'timezone' => config('app.timezone'),
            'max_filesize' => (int) config('filesystems.max_filesize'),
            'welcome_message' => config('app.welcome_message'),
            'home_links' => [],
        ];

        $testObj = new ApplicationConfigurationService;
        $res = $testObj->getConfigSettings();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | updateBasicSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_basic_settings(): void
    {
        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'America/Los_Angeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
            'welcome_message' => 'New Welcome Message',
            'home_links' => [[], [], []],
        ];

        $testObj = new ApplicationConfigurationService;
        $testObj->updateBasicSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.url',
            'value' => $data['url'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.welcome_message',
            'value' => $data['welcome_message'],
        ]);
    }

    public function test_update_basic_settings_no_url_change(): void
    {
        $data = [
            'url' => str_replace('https://', '', config('app.url')),
            'timezone' => 'America/Los_Angeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
            'home_links' => [[], [], []],
        ];

        $testObj = new ApplicationConfigurationService;
        $testObj->updateBasicSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);
    }

    public function test_update_basic_settings_remove_welcome_message(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'app.welcome_message',
            'value' => json_encode('This is a Welcome Message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data = [
            'url' => str_replace('https://', '', config('app.url')),
            'timezone' => 'America/Los_Angeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
            'welcome_message' => null,
            'home_links' => [[], [], []],
        ];

        $testObj = new ApplicationConfigurationService;
        $testObj->updateBasicSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);
        $this->assertDatabaseMissing('app_settings', [
            'key' => 'app.welcome_message',
        ]);
    }

    public function test_update_basic_settings_add_home_links(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'app.welcome_message',
            'value' => json_encode('This is a Welcome Message'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $data = [
            'url' => str_replace('https://', '', config('app.url')),
            'timezone' => 'America/Los_Angeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
            'welcome_message' => null,
            'home_links' => [[
                'url' => 'https://somesite.com',
                'text' => 'This is a website',
            ], [], []],
        ];

        $testObj = new ApplicationConfigurationService;
        $testObj->updateBasicSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.timezone',
            'value' => $data['timezone'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'filesystems.max_filesize',
            'value' => $data['max_filesize'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.company_name',
            'value' => $data['company_name'],
        ]);
        $this->assertDatabaseMissing('app_settings', [
            'key' => 'app.welcome_message',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.home_links',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | getEmailSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_email_settings(): void
    {
        $shouldBe = [
            'from_address' => config('mail.from.address'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
            'username' => config('mail.mailers.smtp.username'),
            'password' => '',
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];

        $testObj = new ApplicationConfigurationService;
        $res = $testObj->getEmailSettings();

        $this->assertEquals($shouldBe, $res);
    }

    public function test_get_email_settings_with_auth_enabled(): void
    {
        config(['mail.mailers.smtp.require_auth' => true]);
        config(['mail.mailers.smtp.username' => 'somedude@noem.com']);
        config(['mail.mailers.smtp.password' => 'this_is_a_password']);

        $shouldBe = [
            'from_address' => config('mail.from.address'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
            'username' => config('mail.mailers.smtp.username'),
            'password' => __('admin.fake_password'),
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];

        $testObj = new ApplicationConfigurationService;
        $res = $testObj->getEmailSettings();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | updateEmailSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_email_settings(): void
    {
        $data = [
            'from_address' => 'new@email.org',
            'username' => 'testName',
            'password' => 'blahBlah',
            'host' => 'randomHost.com',
            'port' => '25',
            'encryption' => 'none',
            'require_auth' => true,
        ];

        $testObj = new ApplicationConfigurationService;
        $testObj->updateEmailSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.from.address',
            'value' => $data['from_address'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.username',
            'value' => $data['username'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.password',
            'value' => $data['password'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.host',
            'value' => $data['host'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.port',
            'value' => $data['port'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.encryption',
            'value' => $data['encryption'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'mail.mailers.smtp.require_auth',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateLogo
    |---------------------------------------------------------------------------
    */
    public function test_update_logo(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'tus/test-upload.png',
            metadata: [
                'name' => 'logo.png',
                'purpose' => 'logo',
                'extension' => 'png',
            ],
        );

        $testObj = new ApplicationConfigurationService;
        $response = $testObj->updateLogo($tusFile);

        $this->assertNotNull($response);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyLogo()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_logo(): void
    {
        AppSettings::create([
            'key' => 'app.logo',
            'value' => json_encode('/storage/images/newLogo.png'),
        ]);

        $testObj = new ApplicationConfigurationService;
        $testObj->destroyLogo();

        $this->assertDatabaseMissing('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}
