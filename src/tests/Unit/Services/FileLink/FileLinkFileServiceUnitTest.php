<?php

namespace Tests\Unit\Services\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\File\FileStorageService;
use App\Services\FileLink\FileLinkFileService;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use Tests\TestCase;

class FileLinkFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createFileLinkFile()
    |---------------------------------------------------------------------------
    */
    public function test_create_file_link_file(): void
    {
        $link = FileLink::factory()->create();
        $file = FileUpload::factory()->create();

        $testObj = new FileLinkFileService;
        $testObj->createFileLinkFile($link, $file, 'Billy Bob');

        $this->assertDatabaseHas('file_link_files', [
            'file_id' => $file->file_id,
            'link_id' => $link->link_id,
        ]);

        $this->assertDatabaseHas('file_link_timelines', [
            'link_id' => $link->link_id,
            'added_by' => 'Billy Bob',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | moveFileLinkFile()
    |---------------------------------------------------------------------------
    */
    public function test_move_file_link_file(): void
    {
        $link = FileLink::factory()->create();
        $customer = Customer::factory()->create();
        $file = FileUpload::factory()->create();
        $user = User::factory()->create();
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

        /** @var FileLinkFileService */
        $testObj = $this->partialMock(FileLinkFileService::class, function (MockInterface $mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('moveDiskFile')
                ->once()
                ->andReturn(true);
        });

        $testObj->moveFileLinkFile(collect($data), $link, $file, $user);

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
    }

    /*
    |---------------------------------------------------------------------------
    | destroyFileLinkFile()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_file_link_file(): void
    {
        Event::fake();

        $link = FileLink::factory()->create();
        $file = FileUpload::factory()->create();
        $link->Files()->attach($file, [
            'timeline_id' => FileLinkTimeline::create([
                'link_id' => $link->link_id,
                'added_by' => 'bob'
            ])->timeline_id,
            'upload' => true,
        ]);

        $testObj = new FileLinkFileService;
        $testObj->destroyFileLinkFile($link, $file);

        $this->assertDatabaseMissing('file_link_files', [
            'link_id' => $link->link_id,
            'file_id' => $file->file_id,
        ]);

        Event::assertDispatched(FileDataDeletedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | checkLinkFileFolder()
    |---------------------------------------------------------------------------
    */
    public function test_check_link_file_folder(): void
    {
        $link = FileLink::factory()->create();
        $fileList = FileUpload::factory()->count(5)->create([
            'disk' => 'fileLinks',
            'folder' => 'tmp',
        ]);
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'yer mom',
        ]);

        $link->Files()->attach($fileList->pluck('file_id')->toArray(), [
            'timeline_id' => $timeline->timeline_id,

        ]);

        /** @var FileLinkFileService */
        $testObj = $this->partialMock(
            FileLinkFileService::class,
            function (MockInterface $mock) use ($link) {
                $mock->shouldReceive('moveUploadedFile')
                    ->times(5)
                    ->with(FileUpload::class, $link->link_id);
            }
        );

        $testObj->checkLinkFileFolder($link);
    }

    /*
    |---------------------------------------------------------------------------
    | checkLinkFilePermission()
    |---------------------------------------------------------------------------
    */
    public function test_check_link_file_permissions(): void
    {
        $link = FileLink::factory()->create();
        $fileList = FileUpload::factory()->count(5)->create([
            'disk' => 'fileLinks',
            'folder' => 'tmp',
            'public' => false,
        ]);
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'yer mom',
        ]);

        $link->Files()->attach($fileList->pluck('file_id')->toArray(), [
            'timeline_id' => $timeline->timeline_id,

        ]);

        $testObj = new FileLinkFileService;
        $testObj->checkLinkFilePermission($link);

        foreach ($fileList as $file) {
            $this->assertDatabaseHas('file_uploads', [
                'file_id' => $file->file_id,
                'public' => true,
            ]);
        }
    }
}
