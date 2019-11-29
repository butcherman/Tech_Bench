<?php

namespace Tests\Feature\TechTips;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\TechTips;
use App\TechTipSystems;
use App\TechTipComments;
use App\TechTipFiles;
use App\UserPermissions;
use App\User;
use App\SystemTypes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class EditTipTest extends TestCase
{
    protected $tip, $files;

    public function setUp():void
    {
        Parent::setUp();

        $this->tip = factory(TechTips::class)->create();
        factory(TechTipSystems::class)->create([
            'tip_id' => $this->tip->tip_id,
        ]);
        $this->files = factory(TechTipFiles::class, 5)->create([
            'tip_id' => $this->tip->tip_id,
        ]);
    }

    //  Try to visit the tip edit page as a guest
    public function test_edit_page_as_guest()
    {
        $response = $this->get(route('tips.edit', $this->tip->tip_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to visit th etip edit page wihout permissions
    public function test_edit_page_no_permissions()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 0,
                'edit_tech_tip'       => 0,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );
        $response = $this->actingAs($user)->get(route('tips.edit', $this->tip->tip_id));

        $response->assertStatus(403);
    }

    //  Try to visit the edit page - valid
    public function test_edit_page()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 1,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );
        $response = $this->actingAs($user)->get(route('tips.edit', $this->tip->tip_id));

        $response->assertSuccessful();
        $response->assertViewIs('tips.editTip');
    }

    //  Try to visit the edit page with an invalid tip id
    public function test_edit_page_bad_tip_id()
    {
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 1,
                'edit_tech_tip'       => 1,
                'delete_tech_tip'     => 1,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );
        $response = $this->actingAs($user)->get(route('tips.edit', 548646546876551));

        $response->assertSuccessful();
        $response->assertViewIs('tips.tipNotFound');
    }

    //  Try to submit edit tech tip as a guest
    public function test_submit_edit_tip_as_guest()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            '_completed' => 'true',
            'subject' => $tipData->subject,
            'eqipment' => [$systems->sys_id],
            'tipType' => 1,
            'tip' => $tipData->description
        ];

        $response = $this->put(route('tips.update', $this->tip->tip_id), $data);

        // dd($response);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to submit edit tech tip as a user without permissions
    public function test_submit_edit_tip_no_permissions()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject' => $tipData->subject,
            'eqipment' => [$systems->sys_id],
            'tipType' => 1,
            'tip' => $tipData->description
        ];
        $user = factory(User::class)->create();
        factory(UserPermissions::class)->create(
            [
                'user_id'             => $user->user_id,
                'manage_users'        => 0,
                'run_reports'         => 0,
                'add_customer'        => 1,
                'deactivate_customer' => 1,
                'use_file_links'      => 0,
                'create_tech_tip'     => 0,
                'edit_tech_tip'       => 0,
                'delete_tech_tip'     => 0,
                'create_category'     => 0,
                'modify_category'     => 0
            ]
        );

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(403);
    }

    //  Try to submit edited tech tip
    public function test_submit_edit_tip()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description,
            'deletedFileList' => [],
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['tip_id']);
    }

    //  Try to submit tech tip with subject validation error
    public function test_submit_edit_tip_subject_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => '',
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('subject');
    }

    //  Try to submit a new tech tip with equipment validation error
    public function test_submit_edit_tip_equipment_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => '',
            'tipType'   => 1,
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('equipment');
    }

    //  Try to submit tech tip with tipType validation error
    public function test_submit_edit_tip_tipType_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => '',
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tipType');
    }

    //  Try to submit tech tip with tip validation error
    public function test_submit_edit_tip_tip_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => ''
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tip');
    }

    //  Submit a tip that includes a file
    public function test_submit_edit_tip_with_file()
    {
        Storage::fake(config('filesystems.paths.tips'));

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description,
            'file'      => $file = UploadedFile::fake()->image($fileName),
            'deletedFileList' => [],
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertSuccessful();
        $response->assertSeeText('uploaded successfully');

        unset($data['file']);
        $data['_completed'] = true;

        //  After file load completed - a second request is sent to actually create the tip
        $response2 = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response2->assertSuccessful();
        $response2->assertJsonStructure(['tip_id']);

        //  Make sure the file is in the correct place
        $tipID = $response2->getOriginalContent()['tip_id'];
        Storage::disk('local')->assertExists(config('filesystems.paths.tips') . DIRECTORY_SEPARATOR . $tipID . DIRECTORY_SEPARATOR . $fileName);
    }

    //  Try to submit edited tech tip and delete a couple of attached files
    public function test_submit_edit_tip_delete_some_files()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description,
            'deletedFileList' => [
                $this->files[0]->tip_file_id, $this->files[1]->tip_file_id, $this->files[2]->tip_file_id,
            ],
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['tip_id']);
    }
}
