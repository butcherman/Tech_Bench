<?php

namespace Database\Seeders;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Create 21 users.
     */
    public function run(): void
    {
        // Create a tech user if it does not already exist
        User::firstOrCreate(
            [
                'username' => 'tech',
            ],
            [
                'role_id' => 4,
                'first_name' => 'Tech',
                'last_name' => 'User',
                'email' => 'tech@em.com',
                'password' => bcrypt('password'),
                'password_expires' => null,
            ]
        );

        DeviceToken::factory()
            ->count(5)
            ->create(['user_id' => User::first()->user_id]);

        //  Create 10 users each with a different role_id
        User::factory()->count(20)->state(new Sequence(
            ['role_id' => 2],
            ['role_id' => 3],
            ['role_id' => 4]
        ))->has(DeviceToken::factory(rand(0, 5)))->create();

        //  Create 10 users and disable them
        User::factory()->count(5)->trashed()->create();
    }
}
