<?php

namespace Tests\Feature\FileLink\Public;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Models\FileLink;
use App\Models\FileUpload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicFileLinkTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_bad_link(): void
    {
        $response = $this->get(route('guest-link.show', 'random-string'));

        $response->assertSuccessful();
    }

    public function test_show_expired_link(): void
    {
        $link = FileLink::factory()
            ->createQuietly(['expire' => Carbon::yesterday()]);

        $response = $this->get(route('guest-link.show', $link->link_hash));

        $response->assertStatus(410);
    }

    public function test_show_guest(): void
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('guest-link.show', $link->link_hash));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Public/FileLinks/Show')
                    ->has('link-data')
                    ->has('link-files')
            );
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('guest-link.show', $link->link_hash));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('Public/FileLinks/Show')
                    ->has('link-data')
                    ->has('link-files')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_invalid_link(): void
    {
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(
            route('guest-link.update', 'random-string'),
            $data
        );

        $response->assertStatus(404);
    }

    public function test_update_expired_link(): void
    {
        $link = FileLink::factory()
            ->createQuietly(['expire' => Carbon::yesterday()]);
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(
            route('guest-link.update', $link->link_hash),
            $data
        );

        $response->assertStatus(410);
    }

    public function test_update_guest_file_upload(): void
    {
        Storage::fake('fileLinks');

        $link = FileLink::factory()->createQuietly();
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(
            route(
                'guest-link.update',
                $link->link_hash
            ),
            $data
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => $link->link_id,
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('fileLinks')
            ->assertExists($link->link_id . DIRECTORY_SEPARATOR . 'testPhoto.png');
    }

    public function test_update_guest_file_upload_after_file(): void
    {
        Event::fake();

        $upload = FileUpload::factory()->create();
        $link = FileLink::factory()->createQuietly();
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
        ];

        $response = $this->withSession(['link-file' => [$upload->file_id]])
            ->post(
                route(
                    'guest-link.update',
                    $link->link_hash
                ),
                $data
            );

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Files Uploaded Successfully');

        Event::assertDispatched(FileUploadedFromPublicEvent::class);
    }
}
