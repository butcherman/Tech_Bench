<?php

namespace Tests\Unit\Services\TechTip;

use App\Exceptions\Database\RecordInUseException;
use App\Models\TechTip;
use App\Models\TechTipType;
use App\Services\TechTip\TechTipAdministrationService;
use Tests\TestCase;

class TechTipAdministrationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getTechTipSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_tech_tip_settings(): void
    {
        // Default configuration
        $shouldBe = [
            'allow_comments' => true,
            'allow_download' => true,
            'allow_public' => false,
            'public_link_text' => 'Click Here For Our Knowledge Base',
        ];

        $testObj = new TechTipAdministrationService;
        $res = $testObj->getTechTipSettings();

        $this->assertEquals(collect($shouldBe), $res);
    }

    /*
    |---------------------------------------------------------------------------
    | updateTechTipSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_tech_tip_settings(): void
    {
        $data = [
            'allow_comments' => false,
            'allow_download' => false,
            'allow_public' => true,
            'public_link_text' => 'I am random text',
        ];

        $testObj = new TechTipAdministrationService;
        $testObj->updateTechTipSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_comments',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_download',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.allow_public',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'tech-tips.public_link_text',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | createTechTipType()
    |---------------------------------------------------------------------------
    */
    public function test_create_tech_tip_type(): void
    {
        $data = [
            'description' => 'New Tech Tip Type',
        ];

        $testObj = new TechTipAdministrationService;
        $res = $testObj->createTechTipType(collect($data));

        $this->assertEquals($data['description'], $res->description);

        $this->assertDatabaseHas('tech_tip_types', [
            'description' => $data['description'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | updateTechTipType()
    |---------------------------------------------------------------------------
    */
    public function test_update_tech_tip_type(): void
    {
        $tipType = TechTipType::factory()->create();
        $data = [
            'description' => 'New Tech Tip Type',
        ];

        $testObj = new TechTipAdministrationService;
        $res = $testObj->updateTechTipType(collect($data), $tipType);

        $this->assertEquals($data['description'], $res->description);

        $this->assertDatabaseHas('tech_tip_types', [
            'tip_type_id' => $tipType->tip_type_id,
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
        $tipType = TechTipType::factory()->create();

        $testObj = new TechTipAdministrationService;
        $testObj->destroyTechTipType($tipType);

        $this->assertDatabaseMissing('tech_tip_types', $tipType->toArray());
    }

    public function test_destroy_tech_tip_type_in_use(): void
    {
        $tipType = TechTipType::factory()->create();
        TechTip::factory()->create(['tip_type_id' => $tipType->tip_type_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new TechTipAdministrationService;
        $testObj->destroyTechTipType($tipType);

        $this->assertDatabaseHas('tech_tip_types', $tipType->toArray());
    }
}
