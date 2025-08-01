<?php

namespace Tests\Feature\TechTip;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTipFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        Storage::fake('tips');

        $data = TechTip::factory()
            ->make()
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->post(route('tech-tips.upload-file'), $dataArr);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        Storage::fake('tips');

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
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload-file'), $dataArr);

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        Storage::fake('tips');

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
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload-file'), $dataArr);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto.png',
            'public' => false,
        ]);

        Storage::disk('tips')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'testPhoto.png');
    }

    public function test_invoke_duplicate_file(): void
    {
        Storage::fake('tips');
        Storage::disk('tips')
            ->putFileAs(
                'tmp/testPhoto.png',
                UploadedFile::fake()->image('testPhoto.png'),
                'testPhoto.png'
            );

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
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload-file'), $dataArr);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto(1).png',
            'public' => false,
        ]);

        Storage::disk('tips')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'testPhoto(1).png');
    }

    public function test_invoke_public_file(): void
    {
        config(['tech-tips.allow_public' => true]);

        Storage::fake('tips');
        Storage::disk('tips')
            ->putFileAs(
                'tmp/testPhoto.png',
                UploadedFile::fake()->image('testPhoto.png'),
                'testPhoto.png'
            );

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = TechTip::factory()
            ->make(['public' => true])
            ->makeVisible(['tip_type_id'])
            ->makeHidden('views');
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload-file'), $dataArr);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto(1).png',
            'public' => true,
        ]);

        Storage::disk('tips')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'testPhoto(1).png');
    }
}
