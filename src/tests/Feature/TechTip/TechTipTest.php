<?php

namespace Tests\Feature\TechTip;

use App\Exceptions\TechTip\TechTipNotFoundException;
use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Exceptions;
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
            ->make(['public' => false])
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

    public function test_store_public_feature_disabled(): void
    {
        config(['tech-tips.allow_public' => false]);

        $this->changeRolePermission(3, 'Add Public Tech Tip', true);

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

    public function test_store_public_feature_enabled(): void
    {
        config(['tech-tips.allow_public' => true]);

        $this->changeRolePermission(3, 'Add Public Tech Tip', true);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 3]);
        $data = TechTip::factory()
            ->make(['public' => true])
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
            'public'
        ]));
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

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.show', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Show')
                    ->has('allow-comments')
                    ->has('equipment')
                    ->has('files')
                    ->has('is-fav')
                    ->has('permissions')
                    ->has('tech-tip')
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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Show')
                    ->has('allow-comments')
                    ->has('equipment')
                    ->has('files')
                    ->has('is-fav')
                    ->has('permissions')
                    ->has('tech-tip')
            );
    }

    public function test_show_missing_tip(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $this->expectException(TechTipNotFoundException::class);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->get(route('tech-tips.show', 'random-tip'));

        $response->assertStatus(302)
            ->assertRedirect(route('tech-tips.not-found'));

        Exceptions::assertReported(TechTipNotFoundException::class);
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        $this->changeRolePermission(4, 'Edit Tech Tip');

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
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('TechTip/Edit')
                    ->has('tech-tip')
                    ->has('equip-list')
                    ->has('file-list')
                    ->has('permissions')
                    ->has('tip-types')
                    ->has('equip-types')
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
        $data = TechTip::factory()
            ->make(['public' => false])
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];

        $response = $this->put(
            route('tech-tips.update', $tip->tip_id),
            $dataArr
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
        $data = TechTip::factory()
            ->make(['public' => false])
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $dataArr);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $tip = TechTip::factory()->create();
        $data = TechTip::factory()
            ->make(['public' => false])
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $dataArr);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.updated'));

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $tip->tip_id,
            'subject' => $data->subject,
        ]);
    }

    public function test_update_no_public_permission(): void
    {
        $this->changeRolePermission(2, 'Add Public Tech Tip');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $tip = TechTip::factory()->create();
        $data = TechTip::factory()
            ->make()
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];
        $dataArr['public'] = true;

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $dataArr);

        $response->assertForbidden();
    }

    public function test_update_public_feature_disabled(): void
    {
        config(['tech-tips.allow_public' => false]);

        $this->changeRolePermission(2, 'Add Public Tech Tip', true);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $tip = TechTip::factory()->create();
        $data = TechTip::factory()
            ->make()
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];
        $dataArr['public'] = true;

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $dataArr);

        $response->assertForbidden();
    }

    public function test_update_public_feature_enabled(): void
    {
        config(['tech-tips.allow_public' => true]);

        $this->changeRolePermission(2, 'Add Public Tech Tip', true);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $tip = TechTip::factory()->create();
        $data = TechTip::factory()
            ->make()
            ->makeHidden('views')
            ->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();

        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['removedFiles'] = [];
        $dataArr['public'] = true;

        $response = $this->actingAs($user)
            ->put(route('tech-tips.update', $tip->tip_id), $dataArr);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('tips.updated'));

        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $tip->tip_id,
            'subject' => $data->subject,
        ]);
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
            ->assertSessionHas('danger', __('tips.deleted'));

        $this->assertSoftDeleted('tech_tips', $tip->only(['tip_id']));
    }

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
