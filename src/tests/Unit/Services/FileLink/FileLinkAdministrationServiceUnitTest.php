<?php

namespace Tests\Unit\Services\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Services\FileLink\FileLinkAdministrationService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FileLinkAdministrationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getFileLinkSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_file_link_settings(): void
    {
        config(['file-link.feature_enabled' => true]);

        $shouldBe = [
            'feature_enabled' => (bool) config('file-link.feature_enabled'),
            'default_link_life' => config('file-link.default_link_life'),
            'auto_delete' => (bool) config('file-link.auto_delete'),
            'auto_delete_days' => config('file-link.auto_delete_days'),
            'auto_delete_override' => (bool) config('file-link.auto_delete_override'),
        ];

        $testObj = new FileLinkAdministrationService;
        $res = $testObj->getFileLinkSettings();

        $this->assertEquals($shouldBe, $res);
    }

    public function test_save_file_link_settings(): void
    {
        Event::fake();

        config(['file-link.feature_enabled' => true]);

        $data = [
            'feature_enabled' => true,
            'default_link_life' => 45,
            'auto_delete' => false,
            'auto_delete_days' => 45,
            'auto_delete_override' => false,
        ];

        $testObj = new FileLinkAdministrationService;
        $testObj->saveFileLinkSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.default_link_life',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_days',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'file-link.auto_delete_override',
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }
}
