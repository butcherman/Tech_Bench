<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Domains\Admin\SettingsDomain;
use App\Http\Requests\Settings\GeneralSettingsRequest;

class SettingsDomainTest extends TestCase
{
    protected $testObj;

    public function setUp():void
    {
        Parent::setup();

        $this->testObj = new SettingsDomain;
    }

    public function test_update_settings()
    {
        $res = $this->testObj->updateSettings('test.key', 'test.value');

        $this->assertTrue($res);
        $this->assertDatabaseHas('settings', ['key' => 'test.key', 'value' => 'test.value']);
    }

    public function test_submit_new_settings()
    {
        $data = new GeneralSettingsRequest([
            'timezone' => 'America/Log_Angeles',
            'filesize' => 500,
        ]);

        $res = $this->testObj->submitNewSettings($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('settings', ['key' => 'app.timezone', 'value' => 'America/Log_Angeles']);
        $this->assertDatabaseHas('settings', ['key' => 'filesystems.paths.max_size', 'value' => 500]);
    }

    public function test_save_new_logo()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('newLogo.png');
        $res = $this->testObj->saveNewLogo($logo);
        $this->assertEquals($res, config('app.url').'/storage/images/logo/newLogo.png');

        Storage::disk('public')->assertExists('images/logo/newLogo.png');
    }
}
