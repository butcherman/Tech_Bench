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
    public function test_invoke_guest()
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

    public function test_invoke_no_permission()
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

    public function test_invoke()
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
        ]);

        Storage::disk('tips')
            ->assertExists('tmp' . DIRECTORY_SEPARATOR . 'testPhoto.png');
    }
}
