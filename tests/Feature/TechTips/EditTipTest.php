<?php

namespace Tests\Feature\TechTips;

use App\TechTips;
use Tests\TestCase;
use App\SystemTypes;
use App\TechTipFiles;
use App\TechTipSystems;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $user     = $this->userWithoutPermission('Edit Tech Tip');
        $response = $this->actingAs($user)->get(route('tips.edit', $this->tip->tip_id));

        $response->assertStatus(403);
    }

    //  Try to visit the edit page - valid
    public function test_edit_page()
    {
        $user     = $this->getInstaller();
        $response = $this->actingAs($user)->get(route('tips.edit', $this->tip->tip_id));

        $response->assertSuccessful();
        $response->assertViewIs('tips.editTip');
    }

    //  Try to visit the edit page with an invalid tip id
    public function test_edit_page_bad_tip_id()
    {
        $user = $this->getInstaller();
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
            'subject' => $tipData->subject,
            'eqipment' => [$systems->sys_id],
            'tipType' => 1,
            'tip' => $tipData->description,
        ];

        $response = $this->put(route('tips.update', $this->tip->tip_id), $data);

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
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => $systems,
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user     = $this->userWithoutPermission('Edit Tech Tip');
        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(403);
    }

    //  Try to submit edited tech tip
    public function test_submit_edit_tip()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => [$systems],
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Try to submit tech tip with subject validation error
    public function test_submit_edit_tip_subject_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => null,
            'system_types'    => [$systems],
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('subject');
    }

    //  Try to submit a new tech tip with equipment validation error
    public function test_submit_edit_tip_equipment_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => null,
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('system_types');
    }

    //  Try to submit tech tip with tipType validation error
    public function test_submit_edit_tip_tipType_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => [$systems],
            'tip_type_id'     => null,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('tip_type_id');
    }

    //  Try to submit tech tip with tip validation error
    public function test_submit_edit_tip_tip_validation_error()
    {
        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => [$systems],
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => null,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('description');
    }

    //  Submit a tip that includes a file
    public function test_submit_edit_tip_with_file()
    {
        Storage::fake(config('filesystems.paths.tips'));

        $tipData = factory(TechTips::class)->make();
        $systems = factory(SystemTypes::class)->create();
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'tip_id'          => $this->tip->tip_id,
            'subject'         => $tipData->subject,
            'system_types'    => [$systems],
            'tip_type_id'     => $tipData->tip_type_id,
            'description'     => $tipData->description,
            'deletedFileList' => null,
            'sticky'          => false,
            'resendNotivication' => false,
            'file'            => $file = UploadedFile::fake()->image($fileName),
        ];
        $user = $this->getInstaller();

        $response = $this->actingAs($user)->put(route('tips.update', $this->tip->tip_id), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}
