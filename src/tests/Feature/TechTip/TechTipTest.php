<?php

namespace Tests\Feature\TechTip;

use App\Events\File\FileUploadDeletedEvent;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\TechTipFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TechTipTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        $response = $this->get(route('tech-tips.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('tech-tips.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Index')
                ->has('filter-data.tip_types')
                ->has('filter-data.equip_types')
                ->has('can-create')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        $response = $this->get(route('tech-tips.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        $this->changeRolePermission(4, 'Add Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)->get(route('tech-tips.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)->get(route('tech-tips.create'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Create')
                ->has('tip-types')
                ->has('permissions')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $response = $this->post(route('tech-tips.store'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        $this->changeRolePermission(4, 'Add Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();

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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Event::fake(NotifiableTechTipEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.created'));

        $this->assertDatabaseHas('tech_tips', collect($data)->only([
            'subject',
            'tip_type_id',
            'details',
            'sticky',
        ])->toArray());

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    public function test_store_no_public_permission(): void
    {
        $this->changeRolePermission(4, 'Add Public Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 3]);

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
            'suppress' => false,
            'sticky' => false,
            'public' => true,
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $data);

        $response->assertForbidden();
    }

    public function test_store_with_files(): void
    {
        Event::fake(NotifiableTechTipEvent::class);
        Storage::fake('tips');
        Storage::disk('tips')
            ->put('tmp/tmp.png', UploadedFile::fake()->image('tmp.png'));

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $fileList = FileUpload::factory()->create([
            'disk' => 'tips',
            'folder' => 'tmp',
            'file_name' => 'tmp.png',
        ]);

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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
        ];

        $response = $this->actingAs($user)
            ->withSession(['tip-file' => [$fileList->file_id]])
            ->post(route('tech-tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('tips.created'));

        $this->assertDatabaseHas('tech_tips', collect($data)->only([
            'subject',
            'tip_type_id',
            'details',
            'sticky',
        ])->toArray());

        $this->assertDatabaseHas('tech_tip_files', [
            'file_id' => $fileList->file_id,
        ]);

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.show', $tip->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_by_slug(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.show', $tip->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Show')
                ->has('tip-data')
                ->has('tip-equipment')
                ->has('tip-files')
                ->has('tip-comments')
                ->has('permissions')
                ->has('is-fav')
            );
    }

    public function test_show_by_id(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.show', $tip->tip_id));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Show')
                ->has('tip-data')
                ->has('tip-equipment')
                ->has('tip-files')
                ->has('tip-comments')
                ->has('permissions')
                ->has('is-fav')
            );
    }

    public function test_show_missing_tip(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.show', 'random-tip'));

        $response->assertStatus(302)
            ->assertRedirect(route('tech-tips.not-found'));
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.edit', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        $this->changeRolePermission(4, 'Edit Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.edit', $tip->slug));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tech-tips.edit', $tip->slug));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('TechTips/Edit')
                ->has('tip-data')
                ->has('tip-types')
                ->has('permissions')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $tip = TechTip::factory()->create();
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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'removedFiles' => [],
        ];

        $response = $this->put(
            route('tech-tips.update', $tip->tip_id),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();
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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'removedFiles' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        Event::fake(NotifiableTechTipEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();
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
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'removedFiles' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.updated'));

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $tip->tip_id,
            'subject' => $data['subject'],
        ]);

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    public function test_update_no_public_permission(): void
    {
        $this->changeRolePermission(4, 'Add Public Tech Tip', false);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $tip = TechTip::factory()->create();
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
            'suppress' => false,
            'sticky' => false,
            'public' => true,
            'removedFiles' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $data);

        $response->assertForbidden();
    }

    public function test_update_with_files(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $techTip = TechTip::factory()
            ->has(FileUpload::factory(2))
            ->has(EquipmentType::factory(2))
            ->create();
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

        $response = $this->actingAs($user)
            ->withSession(['tip-file' => $fileList])
            ->put(route('tech-tips.update', $techTip->tip_id), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.updated'));

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
                'tip_id' => $techTip->tip_id,
                'equip_id' => $equip,
            ]);
        }

        // Verify new files are added
        foreach ($fileList as $file) {
            $this->assertDatabaseHas('tech_tip_files', [
                'tip_id' => $techTip->tip_id,
                'file_id' => $file,
            ]);
        }

        // Verify original file still exists
        $this->assertDatabaseHas('tech_tip_files', [
            'tip_id' => $techTip->tip_id,
            'file_id' => $techTip->FileUpload[1]->file_id,
        ]);

        // Verify removed file is gone
        $this->assertDatabaseMissing('tech_tip_files', [
            'tip_id' => $techTip->tip_id,
            'file_id' => $techTip->FileUpload[0]->file_id,
        ]);

        Event::assertDispatched(NotifiableTechTipEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->delete(route('tech-tips.destroy', $tip->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('tech-tips.destroy', $tip->slug));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('tech-tips.destroy', $tip->slug));

        $response->assertStatus(302)
            ->assertRedirect(route('tech-tips.index'))
            ->assertSessionHas('warning', __('tips.deleted'));

        $this->assertSoftDeleted('tech_tips', $tip->only(['tip_id']));
    }

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    public function test_restore_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('admin.tech-tips.restore', $tip->tip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.restore', $tip->tip_id));

        $response->assertForbidden();
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.tech-tips.restore', $tip->tip_id));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.restored'));

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $tip->tip_id,
            'deleted_at' => null,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Force Delete Method
    |---------------------------------------------------------------------------
    */
    public function test_force_delete_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->delete(
            route('admin.tech-tips.force-delete', $tip->tip_id)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $tip = TechTip::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('admin.tech-tips.force-delete', $tip->tip_id));

        $response->assertForbidden();
    }

    public function test_force_delete(): void
    {
        Event::fake(FileUploadDeletedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();

        TechTipFile::factory()->count(5)->create(['tip_id' => $tip->tip_id]);

        $response = $this->actingAs($user)
            ->delete(route('admin.tech-tips.force-delete', $tip->tip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('admin.tech-tips.deleted-tips'))
            ->assertSessionHas('warning', __('tips.deleted'));

        $this->assertDatabaseMissing('tech_tips', [
            'tip_id' => $tip->tip_id,
        ]);

        Event::assertDispatched(FileUploadDeletedEvent::class);
    }
}
