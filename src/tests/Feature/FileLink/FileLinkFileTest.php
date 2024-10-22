<?php

namespace Tests\Feature\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FileLinkFileTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('links.add-file', $link->link_id), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_store_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_store()
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);

        $response->assertSuccessful();

        // $this->assertDatabaseHas('file_link_timelines', [
        //     'link_id' => $link->link_id,
        // ]);
        // $this->assertDatabaseHas('file_link_files', [
        //     'link_id' => $link->link_id,
        //     'upload' => false,
        // ]);
    }

    // TODO - Verify file exists and is in correct folder after upload complete

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        config(['file-link.feature_enabled' => true]);

        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $file = FileUpload::factory()->create();
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'Outside User',
        ]);

        $attachedFile = new FileLinkFile;
        $attachedFile->link_id = $link->link_id;
        $attachedFile->file_id = $file->file_id;
        $attachedFile->timeline_id = $timeline->timeline_id;
        $attachedFile->upload = true;
        $attachedFile->save();

        $response = $this->delete(
            route('links.destroy-file', [
                $link->link_id,
                $attachedFile->link_file_id,
            ])
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $file = FileUpload::factory()->create();
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'Outside User',
        ]);

        $attachedFile = new FileLinkFile;
        $attachedFile->link_id = $link->link_id;
        $attachedFile->file_id = $file->file_id;
        $attachedFile->timeline_id = $timeline->timeline_id;
        $attachedFile->upload = true;
        $attachedFile->save();

        $response = $this->actingAs($user)
            ->delete(
                route('links.destroy-file', [
                    $link->link_id,
                    $attachedFile->link_file_id,
                ])
            );

        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $file = FileUpload::factory()->create();
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'Outside User',
        ]);

        $attachedFile = new FileLinkFile;
        $attachedFile->link_id = $link->link_id;
        $attachedFile->file_id = $file->file_id;
        $attachedFile->timeline_id = $timeline->timeline_id;
        $attachedFile->upload = true;
        $attachedFile->save();

        $response = $this->actingAs($user)
            ->delete(
                route('links.destroy-file', [
                    $link->link_id,
                    $attachedFile->link_file_id,
                ])
            );

        $response->assertForbidden();
    }

    public function test_destroy_as_admin()
    {
        config(['file-link.feature_enabled' => true]);
        Event::fake();

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $file = FileUpload::factory()->create();
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'Outside User',
        ]);

        $attachedFile = new FileLinkFile;
        $attachedFile->link_id = $link->link_id;
        $attachedFile->file_id = $file->file_id;
        $attachedFile->timeline_id = $timeline->timeline_id;
        $attachedFile->upload = true;
        $attachedFile->save();

        $response = $this->actingAs($actingAs)
            ->delete(
                route('links.destroy-file', [
                    $link->link_id,
                    $attachedFile->link_file_id,
                ])
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $attachedFile->link_file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }

    public function test_destroy()
    {
        config(['file-link.feature_enabled' => true]);
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $file = FileUpload::factory()->create();
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'Outside User',
        ]);

        $attachedFile = new FileLinkFile;
        $attachedFile->link_id = $link->link_id;
        $attachedFile->file_id = $file->file_id;
        $attachedFile->timeline_id = $timeline->timeline_id;
        $attachedFile->upload = true;
        $attachedFile->save();

        $response = $this->actingAs($user)
            ->delete(
                route('links.destroy-file', [
                    $link->link_id,
                    $attachedFile->link_file_id,
                ])
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $attachedFile->link_file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }
}
