<?php

namespace Database\Factories;

use App\Models\FileUploads;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FileUploadsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = FileUploads::class;

    /**
     * Define the model's default state
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
