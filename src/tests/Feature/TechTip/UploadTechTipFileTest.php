<?php

namespace Tests\Feature\TechTip;

use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTechTipFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $equip = EquipmentType::factory()->create()->equip_id;
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => [$equip],
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'file' => UploadedFile::fake()->image('test-image.png'),
        ];

        $response = $this->post(route('tech-tips.upload'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_small_file(): void
    {
        Storage::fake('tips');

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create()->equip_id;
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => [$equip],
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'file' => UploadedFile::fake()->image('test-image.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload'), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'test-image.png',
        ]);

        Storage::disk('tips')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'test-image.png');
    }

    public function test_invoke_duplicate_file(): void
    {
        Storage::fake('tips');
        Storage::disk('tips')->putFileAs(
            'tmp',
            UploadedFile::fake()->image('test-image.png'),
            'test-image.png'
        );

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create()->equip_id;
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => [$equip],
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'file' => UploadedFile::fake()->image('test-image.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload'), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => 'tmp',
            'file_name' => 'test-image(1).png',
        ]);

        Storage::disk('tips')
            ->assertExists('tmp'.DIRECTORY_SEPARATOR.'test-image(1).png');
    }

    public function test_invoke_file_chunk(): void
    {
        Storage::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create()->equip_id;
        $data = [
            'subject' => 'Test Tech Tip',
            'tip_type_id' => 1,
            'equipList' => [$equip],
            'details' => 'Tech Tip Details...',
            'suppress' => false,
            'sticky' => false,
            'public' => false,
            'file' => UploadedFile::fake()
                ->image(
                    'test-image.png-40a30240-3d1d-40cf-a8be-668a559a8600.part'
                ),

            'dzuuid' => '40a30240-3d1d-40cf-a8be-668a559a8600',
            'dzchunkindex' => '7',
            'dztotalfilesize' => '186044520',
            'dzchunksize' => '5000000',
            'dztotalchunkcount' => '38',
            'dzchunkbyteoffset' => '35000000',
        ];

        $response = $this->actingAs($user)
            ->post(route('tech-tips.upload'), $data);

        $response->assertSuccessful();
    }
}
