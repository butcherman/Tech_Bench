<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
}
