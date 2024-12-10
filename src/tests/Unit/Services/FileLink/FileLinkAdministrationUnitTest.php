<?php

namespace Tests\Unit\Services\FileLink;

use App\Events\Feature\FeatureChangedEvent;
use App\Services\FileLink\FileLinkAdministrationService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FileLinkAdministrationUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getFileLinkSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_file_link_settings(): void
    {
        $shouldBe = [
            'default_link_life' => config('file-link.default_link_life'),
            'auto_delete' => (bool) config('file-link.auto_delete'),
            'auto_delete_days' => config('file-link.auto_delete_days'),
            'auto_delete_override' => (bool) config('file-link.auto_delete_override'),
        ];

        $testObj = new FileLinkAdministrationService;
        $res = $testObj->getFileLinkSettings();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | saveFileLinkSettings()
    |---------------------------------------------------------------------------
    */
    public function test_save_file_link_settings(): void
    {
        Event::fake();

        $data = [
            'default_link_life' => '365',
            'auto_delete' => false,
            'auto_delete_days' => '365',
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
