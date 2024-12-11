<?php

namespace Tests\Unit\Services\FileLink;

use App\Jobs\File\MoveTmpFilesJob;
use App\Models\FileLink;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\FileLink\FileLinkService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class FileLinkUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_create_file_link(): void
    {
        Bus::fake();

        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $testObj = new FileLinkService;
        $res = $testObj->createFileLink(
            collect($data),
            User::factory()->create(),
            []
        );

        $this->assertEquals(
            $data['link_name'],
            $res->link_name
        );

        $this->assertDatabaseHas('file_links', $data);

        Bus::assertNotDispatched(MoveTmpFilesJob::class);
    }

    public function test_create_file_link_with_files(): void
    {
        Bus::fake();

        $fileList = FileUpload::factory()
            ->count(3)
            ->create()
            ->pluck('file_id')
            ->toArray();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $testObj = new FileLinkService;
        $res = $testObj->createFileLink(
            collect($data),
            User::factory()->create(),
            $fileList
        );

        $this->assertEquals(
            $data['link_name'],
            $res->link_name
        );

        $this->assertDatabaseHas('file_links', $data);
        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $res->link_id,
            'file_id' => $fileList[0],
        ]);
        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $res->link_id,
            'file_id' => $fileList[1],
        ]);
        $this->assertDatabaseHas('file_link_files', [
            'link_id' => $res->link_id,
            'file_id' => $fileList[2],
        ]);

        Bus::assertDispatched(MoveTmpFilesJob::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_update_file_link(): void
    {
        $link = FileLink::factory()->create();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
        ];

        $testObj = new FileLinkService;
        $res = $testObj->updateFileLink(collect($data), $link);

        $this->assertEquals(
            $data['link_name'],
            $res->link_name
        );

        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'link_name' => $data['link_name'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyFileLink()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_file_link(): void
    {
        $link = FileLink::factory()->create();

        $testObj = new FileLinkService;
        $testObj->destroyFileLink($link);

        $this->assertDatabaseMissing(
            'file_links',
            $link->only(['link_id', 'link_name'])
        );
    }
}
