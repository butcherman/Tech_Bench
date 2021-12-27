<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\FileUploads;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileUploadsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FileUploads::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'disk'      => 'default',
            'folder'    => 'randomFolder',
            'file_name' => Str::random(5).'.jpg',
            'public'    => $this->faker->boolean(),
        ];
    }
}
