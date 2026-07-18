<?php

namespace Database\Factories;

use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\TechTipFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TechTipFile>
 */
class TechTipFileFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tip_id' => TechTip::factory(),
            'file_id' => FileUpload::factory(),
        ];
    }
}
