<?php

namespace Tests\Unit\Services\TechTip;

use App\Events\File\FileUploadDeletedEvent;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\User;
use App\Services\TechTip\TechTipService;
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
    public function test_create_tech_tip_with_files(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $testEquip = EquipmentType::factory()
            ->count(5)
            ->create()
            ->pluck('equip_id')
            ->toArray();
        $fileList = FileUpload::factory()
            ->count(5)
            ->create()
            ->pluck('file_id')
            ->toArray();
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => $testEquip,
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $testObj = new TechTipService;
        $res = $testObj->createTechTip(collect($data), $user, $fileList);

        $this->assertEquals(
            $res->only(['subject', 'tip_type_id', 'details']),
            collect($data)->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        $this->assertDatabaseHas(
            'tech_tips',
            collect($data)
                ->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        // Verify Equipment is attached
        foreach ($testEquip as $equip) {
            $this->assertDatabaseHas('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        // Verify Files are attached
        foreach ($fileList as $upload) {
            $this->assertDatabaseHas('tech_tip_files', [
                'tip_id' => $res->tip_id,
                'file_id' => $upload,
            ]);
        }

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    public function test_create_tech_tip_no_notification(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $testEquip = EquipmentType::factory()
            ->count(5)
            ->create()
            ->pluck('equip_id')
            ->toArray();
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => $testEquip,
            'details' => 'Tech Tip Details...',
            'suppress' => true,
            'sticky' => false,
            'public' => false,
        ];

        $testObj = new TechTipService;
        $res = $testObj->createTechTip(collect($data), $user, []);

        $this->assertEquals(
            $res->only(['subject', 'tip_type_id', 'details']),
            collect($data)
                ->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        $this->assertDatabaseHas(
            'tech_tips',
            collect($data)
                ->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        // Verify Equipment is attached
        foreach ($testEquip as $equip) {
            $this->assertDatabaseHas('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        Event::assertNotDispatched(NotifiableTechTipEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateTechTip()
    |---------------------------------------------------------------------------
    */
    public function test_update_tech_tip_with_files(): void
    {
        Event::fake();

        $techTip = TechTip::factory()
            ->has(FileUpload::factory(2))
            ->has(EquipmentType::factory(2))
            ->create();
        $user = User::factory()->create();
        $testEquip = EquipmentType::factory()
            ->count(5)
            ->create()
            ->pluck('equip_id')
            ->toArray();
        $fileList = FileUpload::factory()
            ->count(5)
            ->create()
            ->pluck('file_id')
            ->toArray();
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => $testEquip,
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'removedFiles' => [$techTip->FileUpload[0]->file_id],
        ];

        // Old Equipment
        $oldEquip = $techTip->EquipmentType->pluck('equip_id')->toArray();

        $testObj = new TechTipService;
        $res = $testObj->updateTechTip(collect($data), $techTip, $user, $fileList);

        $this->assertEquals(
            $res->only(['subject', 'tip_type_id', 'details']),
            collect($data)->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $techTip->tip_id,
            'subject' => $data['subject'],
            'details' => $data['details'],
            'slug' => Str::slug($data['subject']),
            'updated_id' => $user->user_id,
        ]);

        // Verify new equipment is attached
        foreach ($testEquip as $equip) {
            $this->assertDatabaseHas('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        // Verify old equipment is removed
        foreach ($oldEquip as $equip) {
            $this->assertDatabaseMissing('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        // Verify new files are added
        foreach ($fileList as $file) {
            $this->assertDatabaseHas('tech_tip_files', [
                'tip_id' => $res->tip_id,
                'file_id' => $file,
            ]);
        }

        // Verify original file still exists
        $this->assertDatabaseHas('tech_tip_files', [
            'tip_id' => $res->tip_id,
            'file_id' => $techTip->FileUpload[1]->file_id,
        ]);

        // Verify removed file is gone
        $this->assertDatabaseMissing('tech_tip_files', [
            'tip_id' => $res->tip_id,
            'file_id' => $techTip->FileUpload[0]->file_id,
        ]);

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    public function test_update_tech_tip_no_notification(): void
    {
        Event::fake();

        $duplicate = TechTip::factory()->create();
        $techTip = TechTip::factory()
            ->has(EquipmentType::factory(2))
            ->create();
        $user = User::factory()->create();
        $testEquip = EquipmentType::factory()
            ->count(5)
            ->create()
            ->pluck('equip_id')
            ->toArray();
        $data = [
            'subject' => $duplicate->subject,
            'tip_type_id' => 1,
            'equipList' => $testEquip,
            'details' => 'Tech Tip Details...',
            'suppress' => true,
            'sticky' => false,
            'public' => false,
            'removedFiles' => [],
        ];

        // Old Equipment
        $oldEquip = $techTip->EquipmentType->pluck('equip_id')->toArray();

        $testObj = new TechTipService;
        $res = $testObj->updateTechTip(collect($data), $techTip, $user, []);

        $this->assertEquals(
            $res->only(['subject', 'tip_type_id', 'details']),
            collect($data)->only(['subject', 'tip_type_id', 'details'])
                ->toArray()
        );

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $techTip->tip_id,
            'subject' => $data['subject'],
            'details' => $data['details'],
            'slug' => Str::slug($data['subject']).'-1',
        ]);

        // Verify new equipment is attached
        foreach ($testEquip as $equip) {
            $this->assertDatabaseHas('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        // Verify old equipment is removed
        foreach ($oldEquip as $equip) {
            $this->assertDatabaseMissing('tech_tip_equipment', [
                'tip_id' => $res->tip_id,
                'equip_id' => $equip,
            ]);
        }

        Event::assertNotDispatched(NotifiableTechTipEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyTechTip()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_tech_tip(): void
    {
        Event::fake(FileUploadDeletedEvent::class);

        $techTip = TechTip::factory()
            ->has(EquipmentType::factory(2))
            ->has(FileUpload::factory(5))
            ->create();

        $testObj = new TechTipService;
        $testObj->destroyTechTip($techTip);

        $this->assertSoftDeleted('tech_tips', $techTip->only(['tip_id']));

        Event::assertNotDispatched(FileUploadDeletedEvent::class);
    }

    public function test_destroy_tech_tip_force(): void
    {
        Event::fake(FileUploadDeletedEvent::class);

        $techTip = TechTip::factory()
            ->has(EquipmentType::factory(2))
            ->has(FileUpload::factory(5))
            ->create();
        $techTip->delete();

        $testObj = new TechTipService;
        $testObj->destroyTechTip($techTip, true);

        $this->assertDatabaseMissing('tech_tips', $techTip->only(['tip_id']));

        Event::assertDispatched(FileUploadDeletedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | restoreTechTip()
    |---------------------------------------------------------------------------
    */
    public function test_restore_tech_tip(): void
    {
        $techTip = TechTip::factory()
            ->has(EquipmentType::factory(2))
            ->has(FileUpload::factory(5))
            ->create();
        $techTip->delete();

        $testObj = new TechTipService;
        $testObj->restoreTechTip($techTip);

        $this->assertNotSoftDeleted('tech_tips', $techTip->only(['tip_id']));
    }
}
