<?php

namespace Tests\Unit\TechTips;

use Illuminate\Support\Facades\Notification;

use Tests\TestCase;

use App\Domains\TechTips\SetTechTips;

use App\Http\Requests\TechTipNewTipRequest;
use App\Http\Requests\TechTipEditTipRequest;

use App\TechTips;
use App\SystemTypes;

class SetTechTipsTest extends TestCase
{
    protected $testTip, $testObj;

    public function setUp():void
    {
        Parent::setup();

        $this->testObj = new SetTechTips;
    }


    //  Test creating a new Tech Tip
    public function test_process_new_tip_no_file()
    {
        Notification::fake();

        $testData = factory(TechTips::class)->make();
        $data = new TechTipNewTipRequest([
            'subject'     => $testData->subject,
            'equipment'   => [factory(SystemTypes::class)->create()],
            'tip_type_id' => $testData->tip_type_id,
            'description' => $testData->description,
            'noEmail'     => false,
            'sticky'      => false,
        ]);

        $result = $this->actingAs($this->getTech())->testObj->processNewTip($data);
        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $result,
            'tip_type_id' => $testData->tip_type_id,
            'description' => $testData->description,
        ]);
    }

    //  Test updating an existing tip
    public function test_process_edit_tip()
    {
        $defTip   = factory(TechTips::class)->create();
        $testData = factory(TechTips::class)->make();
        $data = new TechTipEditTipRequest([
            'tip_id'             => $defTip->tip_id,
            'sticky'             => true,
            'subject'            => $testData->subject,
            'system_types'       => [factory(SystemTypes::class)->create()],
            'tip_type_id'        => $testData->tip_type_id,
            'description'        => $testData->description,
            'deletedFileList'    => null,
            'resendNotification' => false,
        ]);

        $result = $this->actingAs($this->getTech())->testObj->processEditTip($data, $defTip->tip_id);
        $this->assertTrue($result);
        $this->assertDatabaseHas('tech_tips', [
            'tip_id'      => $defTip->tip_id,
            'tip_type_id' => $testData->tip_type_id,
            'description' => $testData->description,
            'subject'     => $testData->subject,
        ]);
    }

    //  Test deleting a tech tip
    public function test_delete_tip()
    {
        $tip = factory(TechTips::class)->create();

        $this->assertTrue($this->actingAs($this->getInstaller())->testObj->deleteTip($tip->tip_id));
        $this->assertDatabaseMissing('tech_tips', $tip->toArray());
    }
}
