<?php

namespace Tests\Unit\Services\TechTip;

use App\Events\TechTip\NotifiableTechTipEvent;
use App\Jobs\TechTip\ProcessTipFilesJob;
use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\User;
use App\Services\TechTip\TechTipService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class TechTipServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createTechTip()
    |---------------------------------------------------------------------------
    */
    public function test_create_tech_tip(): void
    {
        Bus::fake();
        Event::fake();

        $user = User::factory()->create();
        $data = [
            'subject' => 'Test Tech Tip Subject',
            'tip_type_id' => 1,
            'equipList' => EquipmentType::factory()
                ->count(2)
                ->create()
                ->pluck('equip_id')
                ->toArray(),
            'details' => 'Tech Tip Body',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $fileList = FileUpload::factory()->count(3)->create()->pluck('file_id')->toArray();

        $testObj = new TechTipService;
        $res = $testObj->createTechTip(collect($data), $user, $fileList);

        $this->assertEquals($data['subject'], $res->subject);

        $this->assertDatabaseHas('tech_tips', [
            'subject' => $data['subject'],
            'tip_type_id' => $data['tip_type_id'],
            'details' => $data['details'],
            'sticky' => $data['sticky'],
            'public' => $data['public'],
            'user_id' => $user->user_id,
        ]);

        foreach ($data['equipList'] as $equipId) {
            $this->assertDatabaseHas('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equipId,
            ]);
        }

        foreach ($fileList as $fileId) {
            $this->assertDatabaseHas('tech_tip_files', [
                'tip_id' => $res->tip_id,
                'file_id' => $fileId,
            ]);
        }

        Bus::assertDispatched(ProcessTipFilesJob::class);
        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    public function test_create_tech_tip_duplicate_slug(): void
    {
        Bus::fake();
        Event::fake();

        $user = User::factory()->create();
        $data = [
            'subject' => 'Test Tech Tip Subject',
            'tip_type_id' => 1,
            'equipList' => EquipmentType::factory()
                ->count(2)
                ->create()
                ->pluck('equip_id')
                ->toArray(),
            'details' => 'Tech Tip Body',
            'suppress' => true,
            'sticky' => false,
            'public' => false,
        ];

        TechTip::factory()->create([
            'subject' => $data['subject'],
            'slug' => Str::slug($data['subject']),
        ]);

        $testObj = new TechTipService;
        $res = $testObj->createTechTip(collect($data), $user, []);

        $this->assertEquals($data['subject'], $res->subject);

        $this->assertDatabaseHas('tech_tips', [
            'subject' => $data['subject'],
            'tip_type_id' => $data['tip_type_id'],
            'details' => $data['details'],
            'sticky' => $data['sticky'],
            'public' => $data['public'],
            'user_id' => $user->user_id,
            'slug' => $res->slug,
        ]);

        Bus::assertNotDispatched(ProcessTipFilesJob::class);
        Event::assertNotDispatched(NotifiableTechTipEvent::class);
    }
}
