<?php

namespace Tests\Feature\Home;

use App\Exceptions\File\FileMissingException;
use App\Exceptions\File\IncorrectFilenameException;
use App\Exceptions\File\PrivateFileException;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Exceptions;
use Tests\TestCase;

class DownloadFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest_bad_filename(): void
    {
        Exceptions::fake();

        $file = FileUpload::factory()->create(['file_name' => 'someFile.png']);

        $this->expectException(IncorrectFilenameException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('download', [$file->file_id, 'blah.jpb']));

        $response->assertForbidden();

        Exceptions::assertReported(IncorrectFilenameException::class);
    }

    public function test_invoke_guest_bad_file_id(): void
    {
        Exceptions::fake();

        $file = FileUpload::factory()->create(['file_name' => 'someFile.png']);

        $this->expectException(ModelNotFoundException::class);

        $response = $this->withoutExceptionHandling()
            ->get(route('download', [84587451, $file->file_name]));

        $response->assertStatus(404);

        Exceptions::assertReported(ModelNotFoundException::class);
    }

    public function test_invoke_guest_private_file(): void
    {
        Exceptions::fake();

        $file = FileUpload::factory()
            ->create(['file_name' => 'someFile.png', 'public' => false]);

        $this->expectException(PrivateFileException::class);

        $response = $this->withoutExceptionHandling()->get(route('download', [
            $file->file_id,
            $file->file_name,
        ]));

        $response->assertForbidden();

        Exceptions::assertReported(PrivateFileException::class);
    }

    public function test_invoke_guest_missing_file(): void
    {
        Exceptions::fake();

        $file = FileUpload::create([
            'disk' => 'local',
            'file_name' => 'testRandomPhoto.png',
            'folder' => 'random_folder',
            'file_size' => 1,
            'public' => true,
        ]);

        $this->expectException(FileMissingException::class);

        $response = $this->withoutExceptionHandling()->get(route('download', [
            $file->file_id,
            $file->file_name,
        ]));

        $response->assertStatus(404);

        Exceptions::assertReported(FileMissingException::class);
    }

    public function test_invoke_bad_filename(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $file = FileUpload::factory()->create(['file_name' => 'someFile.png']);

        $this->expectException(IncorrectFilenameException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get(route('download', [$file->file_id, 'blah.jpb']));

        $response->assertForbidden();

        Exceptions::assertReported(IncorrectFilenameException::class);
    }

    public function test_invoke_bad_file_id(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $file = FileUpload::factory()->create(['file_name' => 'someFile.png']);

        $this->expectException(ModelNotFoundException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get(route('download', [84587451, $file->file_name]));

        $response->assertStatus(404);

        Exceptions::assertReported(ModelNotFoundException::class);
    }

    public function test_invoke_missing_file(): void
    {
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $file = FileUpload::create([
            'disk' => 'local',
            'file_name' => 'testRandomPhoto.png',
            'folder' => 'random_folder',
            'file_size' => 1,
            'public' => false,
        ]);

        $this->expectException(FileMissingException::class);

        $response = $this->actingAs($user)
            ->withoutExceptionHandling()
            ->get(route('download', [$file->file_id, $file->file_name]));

        $response->assertStatus(404);

        Exceptions::assertReported(FileMissingException::class);
    }
}
