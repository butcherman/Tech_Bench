<?php

namespace Tests\Unit\Services\TechTip;

use App\Events\Feature\FeatureChangedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Services\TechTip\TechTipAdministrationService;
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

    /*
    |---------------------------------------------------------------------------
    | createTechTipType()
    |---------------------------------------------------------------------------
    */
    public function test_create_tech_tip_type(): void
    {
        $data = [
            'description' => 'New Tip Type',
        ];

        $testObj = new TechTipAdministrationService;
        $res = $testObj->createTechTipType(collect($data));

        $this->assertEquals($data, $res->only('description'));

        $this->assertDatabaseHas('tech_tip_types', $data);
    }

    /*
    |---------------------------------------------------------------------------
    | updateTechTipType()
    |---------------------------------------------------------------------------
    */
    public function test_update_tech_tip_type(): void
    {
        $existing = TechTipType::factory()->create();
        $data = [
            'description' => 'New Tip Type',
        ];

        $testObj = new TechTipAdministrationService;
        $res = $testObj->updateTechTipType(collect($data), $existing);

        $this->assertEquals($data, $res->only('description'));

        $this->assertDatabaseHas('tech_tip_types', [
            'tip_type_id' => $existing->tip_type_id,
            'description' => $data['description'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyTechTipType()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_tech_tip_type(): void
    {
        $existing = TechTipType::factory()->create();

        $testObj = new TechTipAdministrationService;
        $testObj->destroyTechTipType($existing);

        $this->assertDatabaseMissing('tech_tip_types', $existing->toArray());
    }

    public function test_destroy_tech_tip_type_in_use(): void
    {
        $existing = TechTipType::factory()->create();
        TechTip::factory()->create(['tip_type_id' => $existing->tip_type_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new TechTipAdministrationService;
        $testObj->destroyTechTipType($existing);

        $this->assertDatabaseHas('tech_tip_types', $existing->toArray());
    }
}
