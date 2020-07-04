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
        $this->be($user);

        $res = (new SetTechTips)->processNewTip($data);
        $this->assertNotFalse($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $res]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $res, 'sys_id' => $sys->sys_id]);
        Notification:: assertSentTo([$user], NewTechTipNotification::class);
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
        $this->be($user);

        $res = (new SetTechTips)->processNewTip($data);
        $this->assertNotFalse($res);
        $this->assertDatabaseHas('tech_tips', ['tip_id' => $res]);
        $this->assertDatabaseHas('tech_tip_systems', ['tip_id' => $res, 'sys_id' => $sys->sys_id]);
        Notification:: assertNothingSent();
    }
}
