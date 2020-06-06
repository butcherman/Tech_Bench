<?php

namespace Tests\Unit\Admin;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Domains\Admin\SettingsDomain;

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

    public function test_save_new_logo()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('newLogo.png');
        $res = $this->testObj->saveNewLogo($logo);
        $this->assertEquals($res, config('app.url').'/storage/images/logo/newLogo.png');

        Storage::disk('public')->assertExists('images/logo/newLogo.png');
    }
}
