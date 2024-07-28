<?php

namespace Tests\Feature\TechTips;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTipFileTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('tips');
        $data = TechTip::factory()->make()->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->post(route('tech-tips.upload'), $dataArr);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('tips');

        $permId = UserRolePermissionType::where('description', 'Add Tech Tip')
            ->first()->perm_type_id;
        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', $permId)
            ->update([
                'allow' => false,
            ]);

        $data = TechTip::factory()->make()->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs(User::factory()->create())
            ->post(route('tech-tips.upload'), $dataArr);
        $response->assertForbidden();
    }

    public function test_invoke()
    {
        Storage::fake('tips');
        $data = TechTip::factory()->make()->makeVisible(['tip_type_id']);
        $dataArr = $data->toArray();
        $equipList = EquipmentType::factory()->count(2)->create();
        $dataArr['suppress'] = false;
        $dataArr['equipList'] = $equipList->pluck('equip_id')->toArray();
        $dataArr['file'] = UploadedFile::fake()->image('testPhoto.png');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('tech-tips.upload'), $dataArr);
        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('tips')
            ->assertExists('tmp' . DIRECTORY_SEPARATOR . 'testPhoto.png');
    }
}
