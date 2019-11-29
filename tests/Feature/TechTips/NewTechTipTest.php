<?php

namespace Tests\Feature\TechTips;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\UserPermissions;
use App\TechTips;
use App\SystemTypes;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


class NewTechTipTest extends TestCase
{
    //  Try to access the new tech tip page as guest
    public function test_new_tip_page_as_guest()
    {
        $response = $this->get(route('tips.create'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to access the new tech tip page as user without permissions
    public function test_new_tip_page_no_permissions()
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

        $response = $this->actingAs($user)->get(route('tips.create'));

        $response->assertStatus(403);
    }

    //  Try to access the new tech tip page with proper permissions
    public function test_new_tip_page()
    {
        $user = $this->getTech();
        $response = $this->actingAs($user)->get(route('tips.create'));

        $response->assertSuccessful();
        $response->assertViewIs('tips.create');
    }

    //  Try to submit a new tech tip as a guest
    public function test_submit_new_tip_as_guest()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject' => $tipData->subject,
            'eqipment' => [$systems->sys_id],
            'tipType' => 1,
            'tip' => $tipData->description
        ];

        $response = $this->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Try to submit a new tech tip as a user without permissions
    public function test_submit_new_tip_no_permissions()
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

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(403);
    }

    //  Try to submit a new tech tip
    public function test_submit_new_tip()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['tip_id']);
    }

    //  Try to submit a new tech tip with subject validation error
    public function test_submit_new_tip_subject_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => '',
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('subject');
    }

    //  Try to submit a new tech tip with equipment validation error
    public function test_submit_new_tip_equipment_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => '',
            'tipType'   => 1,
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('equipment');
    }

    //  Try to submit a new tech tip with tipType validation error
    public function test_submit_new_tip_tipType_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => '',
            'tip'       => $tipData->description
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tipType');
    }

    //  Try to submit a new tech tip with tip validation error
    public function test_submit_new_tip_tip_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => ''
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tip');
    }

    //  Submit a tip that includes a file
    public function test_submit_new_tip_with_file()
    {
        Notification::fake();
        Storage::fake(config('filesystems.paths.tips'));

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $fileName = Str::random(5).'.jpg';
        $data = [
            'subject'   => $tipData->subject,
            'equipment' => [$systems],
            'tipType'   => 1,
            'tip'       => $tipData->description,
            'file'      => $file = UploadedFile::fake()->image($fileName)
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertSuccessful();
        $response->assertSeeText('uploaded successfully');

        unset($data['file']);
        $data['_completed'] = true;

        //  After file load completed - a second request is sent to actually create the tip
        $response2 = $this->actingAs($user)->post(route('tips.store'), $data);

        $response2->assertSuccessful();
        $response2->assertJsonStructure(['tip_id']);

        //  Make sure the file is in the correct place
        $tipID = $response2->getOriginalContent()['tip_id'];
        Storage::disk('local')->assertExists(config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID.DIRECTORY_SEPARATOR.$fileName);
    }

    //  Test uploading an image to be used in the tech tip body as a guest
    public function test_image_upload_as_guest()
    {
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
        ];

        $response = $this->post(route('tip.processImage'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Test uploading an image to be used in the tech tip body as a user without permissions
    public function test_image_upload_without_permission()
    {
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
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
            ]);

        $response = $this->actingAs($user)->post(route('tip.processImage'), $data);

        $response->assertStatus(403);
    }

    //  Test uploading an image to be used in the tech tip body
    public function test_image_upload()
    {
        // Storage::disk('public')->fake();

        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tip.processImage'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['location']);
        //  TODO - assert file exists
    }

    //  Test uploading a file other than an image to be used in the tech tip body
    public function test_exe_upload()
    {
        $data = [
            'file' => UploadedFile::fake()->create('virus.exe', 321)
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tip.processImage'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['file']);
    }
}
