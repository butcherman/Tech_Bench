<?php

namespace Database\Seeders;

use App\Models\AppSettings;
use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        // Enable File Link Feature
        AppSettings::create([
            'key' => 'file-link.feature_enabled',
            'value' => json_encode(true),
        ]);

        /**
         * Create 10 File Links for the Administrator User
         */
        $linkList = FileLink::factory()
            ->count(10)
            ->state(
                new Sequence(fn () => [
                    'expire' => Carbon::now()->addDays(rand(-30, 30)),
                ])
            )
            ->create(['user_id' => 1]);

        // Add some files and history
        $linkList->each(function ($link) {
            if (rand(0, 1)) {
                FileLinkFile::factory()->create([
                    'link_id' => $link->link_id,
                    'upload' => false,
                ]);
            }
        });

        /**
         * Create a random number of File Links for 10 random users
         */
        $userList = User::inRandomOrder()->limit(10)->get();

        foreach ($userList as $user) {
            $personalList = FileLink::factory()
                ->count(rand(1, 10))
                ->state(
                    new Sequence(fn () => [
                        'expire' => Carbon::now()->addDays(rand(-15, 90)),
                    ])
                )
                ->create(['user_id' => $user->user_id]);

            // Add some files and history
            $personalList->each(function ($link) {
                if (rand(0, 1)) {
                    FileLinkFile::factory()->create([
                        'link_id' => $link->link_id,
                        'upload' => false,
                    ]);
                }
            });
        }
    }
}
