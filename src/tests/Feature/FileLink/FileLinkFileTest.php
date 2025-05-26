<?php

namespace Tests\Feature\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileLinkFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);

        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('links.files.store', $link->link_id), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.files.store', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_store_no_permission(): void
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
            ->post(route('links.files.store', $link->link_id), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        config(['file-link.feature_enabled' => true]);

        Storage::fake('fileLinks');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data = [
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.files.store', $link->link_id), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $link->link_id,
        ]);

        Storage::disk('fileLinks')
            ->assertExists($link->link_id.'/testPhoto.png');
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        config(['file-link.feature_enabled' => true]);

        $user = User::factory()->create();
        $link = FileLink::factory()->create();
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $data = [
            'cust_id' => $customer->cust_id,
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => $user->user_id,
        ]);

        $link->Files()->attach($file, [
            'timeline_id' => $timeline->timeline_id,
            'upload' => true,
        ]);

        $response = $this->put(
            route('links.files.update', [$link->link_id, $file->file_id]),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $link = FileLink::factory()->create();
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $data = [
            'cust_id' => $customer->cust_id,
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => $user->user_id,
        ]);

        $link->Files()->attach($file, [
            'timeline_id' => $timeline->timeline_id,
            'upload' => true,
        ]);

        $response = $this->actingAs($user)->put(
            route('links.files.update', [$link->link_id, $file->file_id]),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update_no_permission(): void
    {
        config(['file-link.feature_enabled' => true]);
        $this->changeRolePermission(4, 'Use File Links', false);

        /** @var User $user */
        $user = User::factory()->create();
        $link = FileLink::factory()->create();
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $data = [
            'cust_id' => $customer->cust_id,
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => $user->user_id,
        ]);

        $link->Files()->attach($file, [
            'timeline_id' => $timeline->timeline_id,
            'upload' => true,
        ]);

        $response = $this->actingAs($user)->put(
            route('links.files.update', [$link->link_id, $file->file_id]),
            $data
        );

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        config(['file-link.feature_enabled' => true]);

        Storage::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $link = FileLink::factory()->create(['user_id' => $user->user_id]);
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create([
            'disk' => 'fileLinks',
            'file_name' => 'test.txt',
            'folder' => $link->link_id,
        ]);
        $data = [
            'cust_id' => $customer->cust_id,
            'name' => 'Some Name',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        Storage::disk('fileLinks')
            ->put(
                $file->folder.DIRECTORY_SEPARATOR.$file->file_name,
                'Test file contents'
            );

        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => $user->user_id,
        ]);

        $link->Files()->attach($file, [
            'timeline_id' => $timeline->timeline_id,
            'upload' => true,
        ]);

        $response = $this->actingAs($user)->put(
            route('links.files.update', [$link->link_id, $file->file_id]),
            $data
        );

        $response->assertStatus(302)
            ->assertSessionHas('success', 'File Moved to Customer');

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'user_id' => $user->user_id,
            'name' => $data['name'],
            'file_id' => $file->file_id,
        ]);

        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $link->link_id,
            'file_id' => $file->file_id,
            'timeline_id' => $timeline->timeline_id,
            'moved' => 1,
        ]);

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'cust_id' => $customer->cust_id,
        ]);

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $file->file_id,
            'disk' => 'customers',
            'folder' => $customer->cust_id,
            'file_name' => $file->file_name,
        ]);

        Storage::disk('fileLinks')
            ->assertMissing($file->folder.DIRECTORY_SEPARATOR.$file->file_name);

        Storage::disk('customers')
            ->assertExists($customer->cust_id.DIRECTORY_SEPARATOR.$file->file_name);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
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
            route('links.files.destroy', [
                $link->link_id,
                $file->file_id,
            ])
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_feature_disabled(): void
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
                route('links.files.destroy', [
                    $link->link_id,
                    $file->file_id,
                ])
            );

        $response->assertForbidden();
    }

    public function test_destroy_no_permission(): void
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
                route('links.files.destroy', [
                    $link->link_id,
                    $file->file_id,
                ])
            );

        $response->assertForbidden();
    }

    public function test_destroy_as_admin(): void
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
                route('links.files.destroy', [
                    $link->link_id,
                    $file->file_id,
                ])
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $file->file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }

    public function test_destroy(): void
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
                route('links.files.destroy', [
                    $link->link_id,
                    $file->file_id,
                ])
            );

        $response->assertStatus(302)
            ->assertSessionHas('warning', 'File Deleted');

        $this->assertDatabaseMissing('file_link_files', [
            'link_file_id' => $file->file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }
}
