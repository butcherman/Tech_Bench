<?php

namespace Tests\Unit\Services\Admin;

use App\Events\Config\UrlChangedEvent;
use App\Events\Feature\FeatureChangedEvent;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApplicationSettingsUnitTest extends TestCase
{
    public function test_get_basic_settings(): void
    {
        $shouldBe = [
            'url' => preg_replace('(^https?://)', '', config('app.url')),
            'company_name' => config('app.company_name'),
            'timezone' => config('app.timezone'),
            'max_filesize' => (int) config('filesystems.max_filesize'),
        ];

        $testObj = new ApplicationSettingsService;
        $res = $testObj->getBasicSettings();

        $this->assertEquals($shouldBe, $res);
    }

    public function test_update_basic_settings(): void
    {
        Event::fake(UrlChangedEvent::class);

        $data = [
            'url' => 'https://someUrl.noSite',
            'timezone' => 'America/LosAngeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
        ];

        $testObj = new ApplicationSettingsService;
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

        Event::assertDispatched(UrlChangedEvent::class);
    }

    public function test_update_basic_settings_no_url_change(): void
    {
        Event::fake(UrlChangedEvent::class);

        $data = [
            'url' => str_replace('https://', '', config('app.url')),
            'timezone' => 'America/LosAngeles',
            'max_filesize' => '123456',
            'company_name' => 'Bobs Fancy Cats',
        ];

        $testObj = new ApplicationSettingsService;
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

        Event::assertNotDispatched(UrlChangedEvent::class);
    }

    public function test_get_email_settings(): void
    {
        $shouldBe = [
            'from_address' => config('mail.from.address'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => strtoupper(config('mail.mailers.smtp.encryption')),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password')
                ? __('admin.fake_password')
                : '',
            'require_auth' => (bool) config('mail.mailers.smtp.require_auth'),
        ];

        $testObj = new ApplicationSettingsService;
        $res = $testObj->getEmailSettings();

        $this->assertEquals($shouldBe, $res);
    }

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

        $testObj = new ApplicationSettingsService;
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

    public function test_update_feature_settings()
    {
        Event::fake(FeatureChangedEvent::class);

        $data = [
            'file_links' => true,
            'public_tips' => true,
            'tip_comments' => false,
        ];

        $testObj = new ApplicationSettingsService;
        $testObj->updateFeatureSettings(collect($data));

        Event::assertDispatched(FeatureChangedEvent::class);
    }

    public function test_update_logo(): void
    {
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $testObj = new ApplicationSettingsService;
        $testObj->updateLogo(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}