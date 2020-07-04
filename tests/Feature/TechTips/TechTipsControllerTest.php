<?php

namespace Tests\Feature\TechTips;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\TechTips;
use App\SystemTypes;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class TechTipsControllerTest extends TestCase
{
    public function setUp(): void
    {
        Parent::setup();

        $testData = [
            [
                'tip_id'      => 1,
                'public'      => 0,
                'tip_type_id' => 1,
                'subject'     => 'How to do something cool',
                'description' => 'Be Awesome!!!',
            ],
            [
                'tip_id'      => 2,
                'public'      => 0,
                'tip_type_id' => 1,
                'subject'     => 'How to Search for Tech Tips',
                'description' => 'Enter something that should be searched for',
            ],
            [
                'tip_id'      => 3,
                'public'      => 0,
                'tip_type_id' => 2,
                'subject'     => 'Tips for drinking whiskey',
                'description' => 'Drink it straight, or not at all!!!',
            ],
            [
                'tip_id'      => 4,
                'public'      => 0,
                'tip_type_id' => 2,
                'subject'     => 'Network addressing 101',
                'description' => 'An example IP Address would be 192.168.20.23.  A public IP Address would be 77.40.44.97',
            ],
            [
                'tip_id'      => 56896,
                'public'      => 0,
                'tip_type_id' => 3,
                'subject'     => 'Why switch to Laravel',
                'description' => 'Because.  Thats why',
            ],
        ];

        foreach($testData as $data)
        {
            factory(TechTips::class)->create($data);
        }
    }

    public function test_index()
    {
        $response = $this->actingAs($this->getTech())->get(route('tips.index'));
        $response->assertSuccessful();
        $response->assertViewIs('tips.index');
    }

    public function test_index_guest()
    {
        $response = $this->get(route('tips.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_search()
    {
        $data = [
            'search' => [
                'text'   => '56896',
                'type'   => null,
                'sys_id' => null,
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ];

        $response = $this->actingAs($this->getTech())->get(route('tips.search', $data));

        $response->assertSuccessful();
        $response->assertJsonStructure(['data' => [['tip_id', 'sticky', 'tip_type_id', 'subject', 'description', 'summary', 'created_at', 'updated_at']]]);
    }

    public function test_search_guest()
    {
        $data = [
            'search' => [
                'text'   => '56896',
                'type'   => null,
                'sys_id' => null,
            ],
            'pagination' => [
                'low'     => null,
                'high'    => null,
                'perPage' => 25,
            ],
            'page' => 1,
        ];

        $response = $this->get(route('tips.search', $data));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create()
    {
        $response = $this->actingAs($this->getTech())->get(route('tips.create'));
        $response->assertSuccessful();
        $response->assertViewIs('tips.create');
    }

    public function test_create_guest()
    {
        $response = $this->get(route('tips.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_upload_image()
    {
        Storage::fake('public');

        $filename = Str::random(5).'.jpg';
        $data = [
            'file' => UploadedFile::fake()->image($filename),
        ];

        $response = $this->actingAs($this->getTech())->post(route('tips.upload_image'), $data);
        $response->assertSuccessful();
        $response->assertJsonStructure(['location']);
    }

    public function test_upload_image_guest()
    {
        Storage::fake('public');

        $filename = Str::random(5).'.jpg';
        $data = [
            'file' => UploadedFile::fake()->image($filename),
        ];

        $response = $this->post(route('tips.upload_image'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_upload_image_without_permission()
    {
        Storage::fake('public');

        $filename = Str::random(5).'.jpg';
        $data = [
            'file' => UploadedFile::fake()->image($filename),
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Create Tech Tip'))->post(route('tips.upload_image'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Notification::fake();

        $sys  = factory(SystemTypes::class)->create();
        $tip  = factory(TechTips::class)->make();
        $data = [
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'noEmail'     => false,
            'sticky'      => true,
        ];

        $response = $this->actingAs($this->getTech())->post(route('tips.store'), $data);
        $response->assertSuccessful();
        $response->assertJsonStructure(['success', 'tip_id']);
    }

    // public function test_store_with_file()
    // {
    //     Notification::fake();
    //     Storage::fake('local');

    //     $sys  = factory(SystemTypes::class)->create();
    //     $tip  = factory(TechTips::class)->make();
    //     $data = [
    //         'subject'     => $tip->subject,
    //         'equipment'   => [$sys],
    //         'tip_type_id' => 1,
    //         'description' => $tip->description,
    //         'noEmail'     => false,
    //         'sticky'      => true,
    //         'file'        => $file = UploadedFile::fake()->image('image.jpg'),
    //     ];

    //     $response = $this->actingAs($this->getTech())->post(route('tips.store'), $data);
    //     $response->assertSuccessful();
    //     $response->assertJsonStructure(['success', 'tip_id']);

    //     dd($response->getContent());
    // }

    public function test_store_guest()
    {
        Notification::fake();

        $sys  = factory(SystemTypes::class)->create();
        $tip  = factory(TechTips::class)->make();
        $data = [
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'noEmail'     => false,
            'sticky'      => true,
        ];

        $response = $this->post(route('tips.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        Notification::fake();

        $sys  = factory(SystemTypes::class)->create();
        $tip  = factory(TechTips::class)->make();
        $data = [
            'subject'     => $tip->subject,
            'equipment'   => [$sys],
            'tip_type_id' => 1,
            'description' => $tip->description,
            'noEmail'     => false,
            'sticky'      => true,
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Create Tech Tip'))->post(route('tips.store'), $data);
        $response->assertStatus(403);
    }
}
