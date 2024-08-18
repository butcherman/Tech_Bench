<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileLinkFileTest extends TestCase
{
    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('links.add-file', $link->link_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['fileLink.feature_enabled' => false]);

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);
        $response->assertForbidden();
    }

    public function test_store_no_permission()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);
        $response->assertForbidden();
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->post(route('links.add-file', $link->link_id), $data);
        $response->assertSuccessful();

        $this->assertDatabaseHas('file_link_timelines', [
            'link_id' => $link->link_id,
        ]);
        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $link->link_id,
            'upload' => false,
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $user = User::factory()->create();
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

        config(['fileLink.feature_enabled' => true]);

        $response = $this->delete(route('links.destroy-file', [$link->link_id, $attachedFile->link_file_id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled()
    {
        config(['fileLink.feature_enabled' => false]);

        $user = User::factory()->create();
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
            ->delete(route('links.destroy-file', [$link->link_id, $attachedFile->link_file_id]));
        $response->assertForbidden();
    }

    public function test_destroy_no_permission()
    {
        config(['fileLink.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        $user = User::factory()->create();
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

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy-file', [$link->link_id, $attachedFile->link_file_id]));
        $response->assertForbidden();
    }

    public function test_destroy_as_admin()
    {
        config(['fileLink.feature_enabled' => true]);

        $user = User::factory()->create();
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

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('links.destroy-file', [$link->link_id, $attachedFile->link_file_id]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $attachedFile->link_file_id,
        ]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
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

        config(['fileLink.feature_enabled' => true]);

        $response = $this->actingAs($user)
            ->delete(route('links.destroy-file', [$link->link_id, $attachedFile->link_file_id]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $attachedFile->link_file_id,
        ]);
    }
}
