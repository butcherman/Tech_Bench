<?php

namespace Tests\Unit\Models;

use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = FileUpload::factory()
            ->create(['file_name' => 'test.png']);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertTrue(array_key_exists('href', $this->model->toArray()));
        $this->assertTrue(array_key_exists(
            'created_stamp',
            $this->model->toArray()
        ));
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    public function test_file_exists_good(): void
    {
        Storage::fake();
        Storage::disk($this->model->disk)->putFileAs(
            $this->model->folder,
            UploadedFile::fake()->image($this->model->file_name),
            $this->model->file_name
        );

        $this->assertTrue($this->model->fileExists());
    }

    public function test_file_exists_missing(): void
    {
        $this->assertFalse($this->model->fileExists());
    }

    public function test_get_file_path(): void
    {
        $this->assertEquals(
            storage_path(
                'app'.DIRECTORY_SEPARATOR.
                $this->model->folder.DIRECTORY_SEPARATOR.
                $this->model->file_name
            ),
            $this->model->getFilePath()
        );
    }
}
