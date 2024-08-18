<?php

namespace Database\Factories;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileLinkFile>
 */
class FileLinkFileFactory extends Factory
{
    /**
     * Define the model's default state
     */
    public function definition(): array
    {
        return [
            'link_id' => $link = FileLink::factory()->create(),
            'file_id' => FileUpload::factory(),
            'timeline_id' => FileLinkTimeline::create([
                'link_id' => $link->link_id,
                'added_by' => 'Some Dude'
            ]),
            'upload' => true,
        ];
    }
}
