<?php

namespace Database\Factories;

use App\Models\FileUploads;
use App\Models\TechTip;
use App\Models\TechTipFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechTipFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TechTipFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tip_id'  => TechTip::factory()->create()->tip_id,
            'file_id' => FileUploads::factory()->create()->file_id,
        ];
    }
}
