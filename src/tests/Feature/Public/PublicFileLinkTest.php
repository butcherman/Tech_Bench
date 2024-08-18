<?php

namespace Tests\Feature\Public;

use App\Models\FileLink;
use App\Models\User;
use App\Notifications\FileLinks\GuestFileUploadedNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicFileLinkTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_bad_link()
    {
        $response = $this->get(route('guest-link.show', 'random-string'));
        $response->assertStatus(404);
    }

    public function test_show_expired_link()
    {
        $link = FileLink::factory()->create();
        $link->expireLink();

        $response = $this->get(route('guest-link.show', $link->link_hash));
        $response->assertStatus(410);
    }

    public function test_show_guest()
    {
        $link = FileLink::factory()->create();

        $response = $this->get(route('guest-link.show', $link->link_hash));
        $response->assertSuccessful();
    }

    public function test_show()
    {
        $link = FileLink::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('guest-link.show', $link->link_hash));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_invalid_link()
    {
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('guest-link.update', 'random-string'), $data);
        $response->assertStatus(404);
    }

    public function test_update_expired_link()
    {
        $link = FileLink::factory()->create();
        $link->expireLink();
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('guest-link.update', $link->link_hash), $data);
        $response->assertStatus(410);
    }

    public function test_update_guest_file_upload()
    {
        Storage::fake('fileLinks');

        $link = FileLink::factory()->create();
        $data = [
            'name' => 'Billy Bob',
            'note' => 'This is a note',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('guest-link.update', $link->link_hash), $data);
        $response->assertSuccessful();

        $this->assertDatabaseHas('file_uploads', [
            'folder' => $link->link_id,
            'file_name' => 'testPhoto.png',
        ]);

        Storage::disk('fileLinks')
            ->assertExists($link->link_id.DIRECTORY_SEPARATOR.'testPhoto.png');
    }

    public function test_update_guest_after_file()
    {
        Notification::fake();

        $link = FileLink::factory()->create();
        $data = [
            'name' => 'Billy Bob',
            'notes' => 'This is a note',
        ];

        $response = $this->post(route('guest-link.update', $link->link_hash), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Files Uploaded Successfully');

        $this->assertDatabaseHas('file_link_notes', [
            'note' => $data['notes'],
        ]);
        $this->assertDatabaseHas('file_link_timelines', [
            'link_id' => $link->link_id,
            'added_by' => $data['name'],
        ]);

        Notification::assertSentTo($link->User, GuestFileUploadedNotification::class);
    }
}
