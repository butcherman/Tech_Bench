<?php

namespace Tests\Unit\TechTips;

use Tests\TestCase;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use App\TechTips;
use App\SystemTypes;

use App\Domains\TechTips\SetTechTips;

use App\Http\Requests\TechTips\NewTipRequest;
use App\Http\Requests\TechTips\UploadImageRequest;

use App\Notifications\NewTechTipNotification;
use App\Notifications\UpdateTechTipNotification;
use App\TechTipSystems;

class SetTechTipsTest extends TestCase
{
    public function test_process_image()
    {
        Storage::fake('public');

        $filename = Str::random(5).'.jpg';
        $data = new UploadImageRequest([
            'file' => UploadedFile::fake()->image($filename),
        ]);

        $res = (new SetTechTips)->processImage($data);
        $this->assertStringContainsString('/storage/images/tip_img/', $res);
        Storage::disk('public')->assertExists(str_replace('/storage', '', $res));
    }

    public function test_process_new_tip()
    {
        Notification::fake();

        $sys  = factory(SystemTypes::class)->create();
        $tip  = factory(TechTips::class)->make();
        $data = new NewTipRequest([
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'noEmail'     => false,
            'sticky'      => true,
        ]);
        $user = $this->getTech();

        $res = (new SetTechTips)->processNewTip($data, $user->user_id);
        $this->assertNotFalse($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $res]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $res, 'sys_id' => $sys->sys_id]);
        Notification::assertSentTo([$user], NewTechTipNotification::class);
    }

    public function test_process_new_tip_no_notification()
    {
        Notification::fake();

        $sys  = factory(SystemTypes::class)->create();
        $tip  = factory(TechTips::class)->make();
        $data = new NewTipRequest([
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'noEmail'     => true,
            'sticky'      => true,
        ]);
        $user = $this->getTech();

        $res = (new SetTechTips)->processNewTip($data, $user->user_id);
        $this->assertNotFalse($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $res]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $res, 'sys_id' => $sys->sys_id]);
        Notification::assertNothingSent();
    }

    public function test_process_edit_tip()
    {
        Notification::fake();

        $sys      = factory(SystemTypes::class)->create();
        $existing = factory(TechTips::class)->create();
        $existSys = factory(TechTipSystems::class)->create(['tip_id' => $existing->tip_id]);
        $tip      = factory(TechTips::class)->make();
        $data     = new NewTipRequest([
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'notify'      => true,
            'sticky'      => true,
        ]);
        $user = $this->getTech();

        $res = (new SetTechTips)->processEditTip($data, $existing->tip_id, $user->user_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $existing->tip_id, 'subject' => $tip->subject, 'description' => $tip->description]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $existing->tip_id, 'sys_id' => $sys->sys_id]);
        $this->assertDatabaseMissing('tech_tip_systems', ['tip_id' => $existing->tip_id, 'sys_id' => $existSys->sys_id]);
        Notification::assertSentTo([$user], UpdateTechTipNotification::class);
    }

    public function test_process_edit_tip_no_notification()
    {
        Notification::fake();

        $sys      = factory(SystemTypes::class)->create();
        $sys2     = factory(SystemTypes::class)->create();
        $existing = factory(TechTips::class)->create();
        $existSys = factory(TechTipSystems::class)->create(['tip_id' => $existing->tip_id, 'sys_id' => $sys2->sys_id]);
        $tip      = factory(TechTips::class)->make();
        $data     = new NewTipRequest([
            'subject'     => $tip->subject,
            'equipment'   => [$sys, $sys2],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'notify'      => false,
            'sticky'      => true,
        ]);
        $user = $this->getTech();

        $res = (new SetTechTips)->processEditTip($data, $existing->tip_id, $user->user_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $existing->tip_id, 'subject' => $tip->subject, 'description' => $tip->description]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $existing->tip_id, 'sys_id' => $sys->sys_id]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $existing->tip_id, 'sys_id' => $sys2->sys_id]);
        Notification::assertNothingSent();
    }

    public function test_deactivate_tip()
    {
        $tip = factory(TechTips::class)->create();
        $res = (new SetTechTips)->deactivateTip($tip->tip_id);

        $this->assertTrue($res);
        $this->assertSoftDeleted('tech_tips', ['tip_id' => $tip->tip_id]);
    }
}
