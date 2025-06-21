<?php

namespace Database\Factories;

use App\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileUpload>
 */
class FileUploadFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'disk' => 'local',
            'folder' => 'randomFolder',
            'file_name' => Str::random(5).'.jpg',
            'file_size' => 1,
            'public' => $this->faker->boolean(),
        ];
    }

    /**
     * Store a file in the file system matching to stop any exceptions
     */
    public function configure(): static
    {
        return $this->afterCreating(function (FileUpload $file) {
            Storage::disk($file->disk)
                ->putFileAs(
                    $file->folder,
                    UploadedFile::fake()->image($file->file_name),
                    $file->file_name
                );
        });
    }
}
