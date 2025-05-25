<?php

namespace Tests\Unit\Services\FileLink;

use App\Events\File\FileDataDeletedEvent;
use App\Jobs\FileLink\ProcessLinkFilesJob;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\FileLink\FileLinkService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class FileLinkServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_create_file_link(): void
    {
        Bus::fake();

        $user = User::factory()->create();
        $fileList = FileUpload::factory()
            ->count(5)
            ->create()
            ->pluck('file_id')
            ->toArray();
        $data = [
            'link_name' => 'Test Link',
            'expire' => Carbon::now()->addDays(90)->format('Y-m-d'),
            'allow_upload' => true,
            'instructions' => 'Test Link Instruction',
        ];

        $testObj = new FileLinkService;
        $res = $testObj->createFileLink(collect($data), $user, $fileList);

        $this->assertEquals($res->link_name, $data['link_name']);

        $this->assertDatabaseHas('file_links', $data);

        $this->assertDatabaseHas('file_link_timelines', [
            'link_id' => $res->link_id,
        ]);

        $this->assertDatabaseHas('file_link_notes', [
            'timeline_id' => $res->Timeline[0]->timeline_id,
            'note' => 'File Link Created',
        ]);

        foreach ($fileList as $file) {
            $this->assertDatabaseHas('file_link_files', [
                'link_id' => $res->link_id,
                'file_id' => $file,
            ]);
        }

        Bus::assertDispatched(ProcessLinkFilesJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_update_file_link(): void
    {
        $fileLink = FileLink::factory()->create();
        $data = [
            'link_name' => 'Test Link',
            'expire' => Carbon::now()->addDays(90)->format('Y-m-d'),
            'allow_upload' => true,
            'instructions' => 'Test Link Instruction',
        ];

        $testObj = new FileLinkService;
        $res = $testObj->updateFileLink(collect($data), $fileLink);

        $this->assertEquals($res->link_name, $data['link_name']);

        $this->assertDatabaseHas('file_links', [
            'link_id' => $fileLink->link_id,
            'link_name' => $data['link_name'],
            'expire' => $data['expire'],
            'allow_upload' => $data['allow_upload'],
            'instructions' => $data['instructions'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_file_link(): void
    {
        Event::fake(FileDataDeletedEvent::class);

        $link = FileLink::factory()->create();
        FileLinkFile::factory()->count(5)->create(['link_id' => $link->link_id]);

        $testObj = new FileLinkService;
        $testObj->destroyFileLink($link);

        $this->assertDatabaseMissing('file_links', [
            'link_id' => $link->link_id,
        ]);

        Event::assertDispatchedTimes(FileDataDeletedEvent::class, 5);
    }

    /*
    |---------------------------------------------------------------------------
    | extendFileLink();
    |---------------------------------------------------------------------------
    */
    public function test_extend_file_link(): void
    {
        $link = FileLink::factory()->create([
            'expire' => Carbon::now()->addDays(30)->format('Y-m-d'),
        ]);

        $testObj = new FileLinkService;
        $testObj->extendFileLink($link);

        $link->fresh();
        $this->assertEquals(
            $link->expire->toDateString(),
            Carbon::now()->addDays(60)->toDateString(),
        );

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'expire' => Carbon::now()->addDays(60)->toDateString()
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | expireFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_expire_file_link(): void
    {
        $link = FileLink::factory()->create();

        $testObj = new FileLinkService;
        $testObj->expireFileLink($link);

        $link->fresh();

        $this->assertEquals(
            $link->expire->toDateString(),
            Carbon::yesterday()->toDateString()
        );

        $this->assertTrue($link->is_expired);

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'expire' => Carbon::yesterday()->toDateString()
        ]);
    }
}
