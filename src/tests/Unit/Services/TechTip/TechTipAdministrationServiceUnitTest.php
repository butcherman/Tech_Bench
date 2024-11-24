<?php

namespace Tests\Unit\Services\TechTip;

use App\Events\Feature\FeatureChangedEvent;
use App\TechTip\TechTipAdministrationService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TechTipAdministrationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | updateTechTipSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_tech_tip_settings(): void
    {
        Event::fake();

        $data = [
            'allow_comments' => false,
            'allow_public' => true,
        ];

        $testObj = new TechTipAdministrationService;
        $testObj->updateTechTipSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_public',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_comments',
        ]);

        Event::assertDispatched(FeatureChangedEvent::class);
    }
}
