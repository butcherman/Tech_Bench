<?php

namespace Tests\Unit\Models;

use App\Exceptions\FileLink\FileLinkExpiredException;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class FileLinkUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = FileLink::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('is_expired', $this->model->toArray());
        $this->assertArrayHasKey('href', $this->model->toArray());
        $this->assertArrayHasKey('public_href', $this->model->toArray());
        $this->assertArrayHasKey('created_stamp', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_file_upload_relationship(): void
    {
        $fileData = FileLinkFile::factory()
            ->count(5)
            ->create(['link_id' => $this->model->link_id])
            ->pluck('file_id')
            ->toArray();

        $this->assertEquals(
            FileUpload::find($fileData)->toArray(),
            $this->model->FileUpload->makeHidden('pivot')->toArray()
        );
    }

    public function test_user_relationship(): void
    {
        $data = User::where('user_id', $this->model->user_id)->first();

        $this->assertEquals($data->toArray(), $this->model->User->toArray());
    }

    public function test_timeline_relationship(): void
    {
        FileLinkTimeline::create([
            'link_id' => $this->model->link_id,
            'added_by' => 'Some Dude',
        ]);

        $data = FileLinkTimeline::where('link_id', $this->model->link_id)->get();

        $this->assertEquals($data->toArray(), $this->model->Timeline->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    public function test_validate_public_link_good(): void
    {
        $this->assertTrue($this->model->validatePublicLink());
    }

    public function test_validate_public_link_fail(): void
    {
        $this->model->expire = Carbon::yesterday();
        $this->model->save();

        $this->expectException(FileLinkExpiredException::class);

        $this->model->validatePublicLink();
    }

    /*
    |---------------------------------------------------------------------------
    | Prunable Models
    |---------------------------------------------------------------------------
    */
    public function test_prunable(): void
    {
        FileLink::factory()->count(5)->create();
        FileLink::factory()
            ->count(5)
            ->create(['expire' => Carbon::now()->subDays(120)]);

        Artisan::call('model:prune', ['--model' => FileLink::class]);
        $linksLeft = FileLink::all();

        $this->assertCount(6, $linksLeft);
    }

    public function test_prunable_no_override(): void
    {
        config(['file-link.auto_delete_override' => false]);

        FileLink::factory()->count(5)->create();
        FileLink::factory()
            ->count(5)
            ->create(['expire' => Carbon::now()->subDays(120)]);

        Artisan::call('model:prune', ['--model' => FileLink::class]);
        $linksLeft = FileLink::all();

        $this->assertCount(6, $linksLeft);
    }

    public function test_prunable_disabled(): void
    {
        config(['file-link.auto_delete' => false]);


        FileLink::factory()->count(5)->create();
        FileLink::factory()
            ->count(5)
            ->create(['expire' => Carbon::now()->subDays(120)]);

        Artisan::call('model:prune', ['--model' => FileLink::class]);
        $linksLeft = FileLink::all();

        $this->assertCount(11, $linksLeft);
    }
}
