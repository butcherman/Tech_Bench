<?php

namespace Tests\Feature\FileLink;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Exceptions\FileLink\FileLinkExpiredException;
use App\Models\FileLink;
use App\Models\FileUpload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicLinkTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_bad_link(): void
    {
        $response = $this->get(route('guest-link.show', 'random-string'));

        $response->assertStatus(404);
    }

    public function test_show_expired_link(): void
    {
        Exceptions::fake();

        $link = FileLink::factory()
            ->createQuietly(['expire' => Carbon::yesterday()]);

        $this->expectException(FileLinkExpiredException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('guest-link.show', $link->link_hash));

        $response->assertStatus(410);

        Exceptions::assertReported(FileLinkExpiredException::class);
    }

    public function test_show_guest(): void
    {
        $link = FileLink::factory()->createQuietly();

        $response = $this->get(route('guest-link.show', $link->link_hash));

        $response->assertSuccessful()
            ->assertInertia(
                fn(Assert $page) => $page
                    ->component('FileLink/Public/Show')
                    ->has('link')
                    ->has('files')
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
                    ->component('FileLink/Public/Show')
                    ->has('link')
                    ->has('files')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_invalid_link(): void
    {
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(
            route('guest-link.store', 'random-string'),
            $data
        );

        $response->assertStatus(404);
    }

    public function test_store_expired_link(): void
    {
        Exceptions::fake();

        $link = FileLink::factory()
            ->createQuietly(['expire' => Carbon::yesterday()]);
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $this->expectException(FileLinkExpiredException::class);

        $response = $this->withoutExceptionHandling()->post(
            route('guest-link.store', $link->link_hash),
            $data
        );

        $response->assertStatus(410);

        Exceptions::assertReported(FileLinkExpiredException::class);
    }

    public function test_store_guest_file_upload(): void
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
                'guest-link.store',
                $link->link_hash
            ),
            $data
        );

        $response->assertSuccessful()
            ->assertSessionHas('timeline');

        $this->assertDatabaseHas('file_uploads', [
            'folder' => $link->link_id,
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('fileLinks')
            ->assertExists($link->link_id . DIRECTORY_SEPARATOR . 'testPhoto.png');
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_update_invalid_link(): void
    {
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->put(
            route('guest-link.update', 'random-string'),
            $data
        );

        $response->assertStatus(404);
    }

    public function test_update_expired_link(): void
    {
        Exceptions::fake();

        $link = FileLink::factory()
            ->createQuietly(['expire' => Carbon::yesterday()]);
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $this->expectException(FileLinkExpiredException::class);

        $response = $this->withoutExceptionHandling()->put(
            route('guest-link.update', $link->link_hash),
            $data
        );

        $response->assertStatus(410);

        Exceptions::assertReported(FileLinkExpiredException::class);
    }

    public function test_update_guest_file_upload(): void
    {
        Event::fake();

        $link = FileLink::factory()->createQuietly();
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
        ];

        $response = $this->withSession(['timeline' => 1])->put(
            route(
                'guest-link.update',
                $link->link_hash
            ),
            $data
        );

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Files Uploaded');

        Event::assertDispatched(FileUploadedFromPublicEvent::class);
    }
}
