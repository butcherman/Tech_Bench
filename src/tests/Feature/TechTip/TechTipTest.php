<?php

namespace Tests\Feature\TechTip;

use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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

        $response = $this->actingAs($user)
            ->get(route('tech-tips.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Index')
                    ->has('permissions')
                    ->has('filter-data')
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
        $this->changeRolePermission(4, 'Add Tech Tip');

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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Create')
                    ->has('permissions')
                    ->has('tip-types')
                    ->has('equip-types')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $data = TechTip::factory()
            ->make(['public' => false])
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();

        $response = $this->post(route('tech-tips.store'), $dataArr);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        $this->changeRolePermission(4, 'Add Tech Tip');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = TechTip::factory()
            ->make()
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $dataArr);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Notification::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = TechTip::factory()
            ->make()
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $dataArr);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.created'));

        $this->assertDatabaseHas('tech_tips', $data->only([
            'subject',
            'tip_type_id',
            'details',
            'sticky',
        ]));
    }

    public function test_store_no_public_permission(): void
    {
        $this->changeRolePermission(4, 'Add Public Tech Tip');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 3]);
        $data = TechTip::factory()
            ->make()
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['public'] = true;
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();

        $response = $this->actingAs($user)
            ->post(route('tech-tips.store'), $dataArr);

        $response->assertForbidden();
    }

    public function test_store_with_files(): void
    {
        Notification::fake();
        Storage::fake('tips');
        Storage::disk('tips')->put(
            'tmp/tmp.png',
            UploadedFile::fake()->image('tmp.png')
        );

        $fileList = FileUpload::factory()->create([
            'disk' => 'tips',
            'folder' => 'tmp',
            'file_name' => 'tmp.png',
        ]);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = TechTip::factory()
            ->make(['public' => false])
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();

        $response = $this->actingAs($user)
            ->withSession(['tip-file' => [$fileList->file_id]])
            ->post(route('tech-tips.store'), $dataArr);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.created'));

        $this->assertDatabaseHas('tech_tips', $data->only([
            'subject',
            'tip_type_id',
            'details',
            'sticky',
        ]));

        $this->assertDatabaseHas('tech_tip_files', [
            'file_id' => $fileList->file_id,
        ]);
    }

    /**
     * Show Method
     */
    // public function test_show_guest(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->get(route('tech-tips.show', $tip->slug));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_show_by_slug(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->get(route('tech-tips.show', $tip->slug));
    //     $response->assertSuccessful();
    // }

    // public function test_show_by_id(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->get(route('tech-tips.show', $tip->tip_id));
    //     $response->assertSuccessful();
    // }

    // public function test_show_missing_tip(): void
    // {
    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->get(route('tech-tips.show', 'random-tip'));
    //     $response->assertSuccessful();
    // }

    /**
     * Edit Method
     */
    // public function test_edit_guest(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->get(route('tech-tips.edit', $tip->slug));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_edit_no_permission(): void
    // {
    //     $permId = UserRolePermissionType::where('description', 'Edit Tech Tip')
    //         ->first()->perm_type_id;
    //     UserRolePermission::where('role_id', 4)
    //         ->where('perm_type_id', $permId)
    //         ->update([
    //             'allow' => false,
    //         ]);

    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->get(route('tech-tips.edit', $tip->slug));
    //     $response->assertForbidden();
    // }

    // public function test_edit(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->get(route('tech-tips.edit', $tip->slug));
    //     $response->assertSuccessful();
    // }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    // public function test_update_guest(): void
    // {
    //     $tip = TechTip::factory()->create();
    //     $data = TechTip::factory()->create()->makeVisible(['tip_type_id']);
    //     $dataArr = $data->toArray();
    //     $equipList = EquipmentType::factory()->count(2)->create();
    //     $dataArr['suppress'] = false;
    //     $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
    //     $dataArr['removedFiles'] = [];

    //     $response = $this->put(route('tech-tips.update', $tip->tip_id), $dataArr);
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_update_no_permission(): void
    // {
    //     $tip = TechTip::factory()->create();
    //     $data = TechTip::factory()->create()->makeVisible(['tip_type_id']);
    //     $dataArr = $data->toArray();
    //     $equipList = EquipmentType::factory()->count(2)->create();
    //     $dataArr['suppress'] = false;
    //     $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
    //     $dataArr['removedFiles'] = [];

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->put(route('tech-tips.update', $tip->tip_id), $dataArr);
    //     $response->assertForbidden();
    // }

    // public function test_update(): void
    // {
    //     $tip = TechTip::factory()->create();
    //     $data = TechTip::factory()->create()->makeVisible(['tip_type_id']);
    //     $dataArr = $data->toArray();
    //     $equipList = EquipmentType::factory()->count(2)->create();
    //     $dataArr['suppress'] = false;
    //     $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
    //     $dataArr['removedFiles'] = [];

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->put(route('tech-tips.update', $tip->tip_id), $dataArr);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('tips.updated'));

    //     $this->assertDatabaseHas('tech_tips', [
    //         'tip_id' => $tip->tip_id,
    //         'subject' => $data->subject,
    //     ]);
    // }

    // public function test_update_no_public_permission(): void
    // {
    //     $permId = UserRolePermissionType::where('description', 'Add Public Tech Tip')
    //         ->first()->perm_type_id;
    //     UserRolePermission::where('role_id', 2)
    //         ->where('perm_type_id', $permId)
    //         ->update([
    //             'allow' => false,
    //         ]);

    //     $tip = TechTip::factory()->create();
    //     $data = TechTip::factory()->create()->makeVisible(['tip_type_id']);
    //     $dataArr = $data->toArray();
    //     $equipList = EquipmentType::factory()->count(2)->create();
    //     $dataArr['suppress'] = false;
    //     $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
    //     $dataArr['removedFiles'] = [];
    //     $dataArr['public'] = true;

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 2]))
    //         ->put(route('tech-tips.update', $tip->tip_id), $dataArr);
    //     $response->assertForbidden();
    // }

    // public function test_update_with_files(): void
    // {
    //     Storage::fake('tips');
    //     $tip = TechTip::factory()->create();
    //     Storage::disk('tips')->putFileAs($tip->tip_id, UploadedFile::fake()->image('add.png'), '/add.png');
    //     Storage::disk('tips')->putFileAs($tip->tip_id, UploadedFile::fake()->image('remove.png'), '/remove.png');
    //     $file1 = FileUpload::factory()->create([
    //         'disk' => 'tips',
    //         'folder' => $tip->tip_id,
    //         'file_name' => 'add.png',
    //     ]);
    //     $file2 = FileUpload::factory()->create([
    //         'disk' => 'tips',
    //         'folder' => $tip->tip_id,
    //         'file_name' => 'remove.png',
    //     ]);
    //     $tip->FileUpload()->sync([$file2->file_id]);

    //     $data = TechTip::factory()->create()->makeVisible(['tip_type_id']);
    //     $dataArr = $data->toArray();
    //     $equipList = EquipmentType::factory()->count(2)->create();
    //     $dataArr['suppress'] = false;
    //     $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
    //     $dataArr['removedFiles'] = [$file2->file_id];

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->withSession(['tip-file' => [$file1->file_id]])
    //         ->put(route('tech-tips.update', $tip->tip_id), $dataArr);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('tips.updated'));

    //     $this->assertDatabaseHas('tech_tips', [
    //         'tip_id' => $tip->tip_id,
    //         'subject' => $data->subject,
    //     ]);
    //     $this->assertDatabaseHas('tech_tip_files', [
    //         'tip_id' => $tip->tip_id,
    //         'file_id' => $file1->file_id,
    //     ]);
    //     $this->assertDatabaseMissing('tech_tip_files', [
    //         'tip_id' => $tip->tip_id,
    //         'file_id' => $file2->file_id,
    //     ]);

    //     Storage::disk('tips')->assertExists($tip->tip_id.'/add.png');
    //     Storage::disk('tips')->assertMissing($tip->tip_id.'/remove.png');
    // }

    /**
     * Destroy Method
     */
    // public function test_destroy_guest(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->delete(route('tech-tips.destroy', $tip->slug));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_destroy_no_permission(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->delete(route('tech-tips.destroy', $tip->slug));
    //     $response->assertForbidden();
    // }

    // public function test_destroy(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->delete(route('tech-tips.destroy', $tip->slug));
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('warning', __('tips.deleted'));

    //     $this->assertSoftDeleted('tech_tips', $tip->only(['tip_id']));
    // }

    /**
     * Restore Method
     */
    // public function test_restore_guest(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->get(route('admin.tech-tips.restore', $tip->tip_id));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_restore_no_permission(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->get(route('admin.tech-tips.restore', $tip->tip_id));
    //     $response->assertForbidden();
    // }

    // public function test_restore(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->get(route('admin.tech-tips.restore', $tip->tip_id));
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('success', __('tips.restored'));

    //     $this->assertDatabaseHas('tech_tips', [
    //         'tip_id' => $tip->tip_id,
    //         'deleted_at' => null,
    //     ]);
    // }

    /**
     * Force Delete Method
     */
    // public function test_force_delete_guest(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->delete(route('admin.tech-tips.force-delete', $tip->tip_id));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login'));
    //     $this->assertGuest();
    // }

    // public function test_force_delete_no_permission(): void
    // {
    //     $tip = TechTip::factory()->create();

    //     $response = $this->actingAs(User::factory()->createQuietly())
    //         ->delete(route('admin.tech-tips.force-delete', $tip->tip_id));
    //     $response->assertForbidden();
    // }

    // public function test_force_delete(): void
    // {
    //     Storage::fake('tips');
    //     Event::fake();

    //     $tip = TechTip::factory()->create();
    //     TechTipFile::factory()->count(5)->create(['tip_id' => $tip->tip_id]);

    //     $response = $this->actingAs(User::factory()->createQuietly(['role_id' => 1]))
    //         ->delete(route('admin.tech-tips.force-delete', $tip->tip_id));
    //     $response->assertStatus(302);
    //     $response->assertSessionHas('warning', __('tips.deleted'));

    //     $this->assertDatabaseMissing('tech_tips', [
    //         'tip_id' => $tip->tip_id,
    //     ]);
    // }
}
