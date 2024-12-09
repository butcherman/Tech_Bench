<?php

namespace Database\Seeders;

use App\Models\FileLink;
use App\Models\FileLinkFile;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class FileLinkSeeder extends Seeder
{
    use WithFaker;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Create 10 File Links for the Administrator User
         */
        $linkList = FileLink::factory()->count(10)->create(['user_id' => 1]);

        // Add some files and history
        $linkList->each(function ($link) {
            if (rand(0, 1)) {
                FileLinkFile::factory()->create([
                    'link_id' => $link->link_id,
                    'upload' => false,
                ]);
            }
        });
    }
}
