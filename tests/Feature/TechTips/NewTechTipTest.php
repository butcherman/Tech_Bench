<?php

namespace Tests\Feature\TechTips;

use App\TechTips;
use Tests\TestCase;
use App\SystemTypes;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;


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
        $user     = $this->getUserWithoutPermission('Create Tech Tip');
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
            'subject'     => $tipData->subject,
            'eqipment'    => [$systems->sys_id],
            'tip_type_id' => 1,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
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
            'subject'     => $tipData->subject,
            'equipment'   => [$systems->sys_id],
            'tip_type_id' => 1,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
        ];
        $user     = $this->getUserWithoutPermission('Create Tech Tip');
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
            'subject'     => $tipData->subject,
            'equipment'   => [$systems],
            'tip_type_id' => 1,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertSuccessful();
        $response->assertJsonStructure(['success']);
    }

    //  Try to submit a new tech tip with subject validation error
    public function test_submit_new_tip_subject_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'     => '',
            'equipment'   => [$systems],
            'tip_type_id' => 1,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
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
            'subject'     => '',
            'equipment'   => null,
            'tip_type_id' => 1,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
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
            'subject'     => $tipData->subject,
            'equipment'   => [$systems],
            'tip_type_id' => null,
            'description' => $tipData->description,
            'noEmail'     => false,
            'sticky'      => false,
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tip_type_id');
    }

    //  Try to submit a new tech tip with tip validation error
    public function test_submit_new_tip_tip_validation_error()
    {
        Notification::fake();

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'subject'     => $tipData->subject,
            'equipment'   => [$systems],
            'tip_type_id' => 1,
            'description' => null,
            'noEmail'     => false,
            'sticky'      => false,
        ];
        $user = $this->getTech();

        $response = $this->actingAs($user)->post(route('tips.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('description');
    }

    //  Submit a tip that includes a file
    // public function test_submit_new_tip_with_file()
    // {
    //     Notification::fake();
    //     Storage::fake(config('filesystems.paths.tips'));

    //     $tipData = factory(TechTips::class)->make();
    //     $systems = factory(SystemTypes::class)->create();
    //     $fileName = Str::random(5).'.jpg';
    //     $data = [
    //         'subject'     => $tipData->subject,
    //         'equipment'   => [$systems],
    //         'tip_type_id' => 1,
    //         'description' => $tipData->description,
    //         'noEmail'     => false,
    //         'sticky'      => false,
    //         'file'        => $file = UploadedFile::fake()->image($fileName)
    //     ];
    //     $user = $this->getTech();

    //     $response = $this->actingAs($user)->post(route('tips.store'), $data);

    //     $response->assertSuccessful();

    //     unset($data['file']);
    //     $data['_completed'] = true;

    //     //  After file load completed - a second request is sent to actually create the tip
    //     $response2 = $this->actingAs($user)->post(route('tips.store'), $data);

    //     $response2->assertSuccessful();
    //     $response2->assertJsonStructure(['success']);

    //     //  Make sure the file is in the correct place
    //     $tipID = $response2->getOriginalContent()['success'];
    //     Storage::disk('local')->assertExists(config('filesystems.paths.tips').DIRECTORY_SEPARATOR.$tipID.DIRECTORY_SEPARATOR.$fileName);
    // }

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
        $user     = $this->getUserWithoutPermission('Create Tech Tip');
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
