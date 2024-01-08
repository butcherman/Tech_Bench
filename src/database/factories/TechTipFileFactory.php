<?php

namespace Database\Factories;

use App\Models\FileUploads;
use App\Models\TechTip;
use App\Models\TechTipFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechTipFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model
     */
    protected $model = TechTipFile::class;

    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'file_id' => FileUploads::factory(),
        ];
    }
}
