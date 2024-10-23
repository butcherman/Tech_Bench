<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use AppSettingsTrait;

    /**
     *  Create 20 random users with different roles
     */
    public function run(): void
    {
        //  Make it so that the admin password is not expired
        User::find(1)->update([
            'password_expires' => null,
        ]);

        //  Turn off the "first time setup" flag
        $this->clearSetting('app.first_time_setup');

        //  Create a Tech User that has restricted access if it does not already exist
        $techUser = User::where('username', 'tech')
            ->orWhere('email', 'tech@em.com')
            ->first();

        if (! $techUser) {
            User::create([
                'role_id' => 4,
                'username' => 'tech',
                'first_name' => 'Tech',
                'last_name' => 'User',
                'email' => 'tech@em.com',
                'password' => bcrypt('password'),
                'password_expires' => null,
            ]);
        }

        //  Create 10 users each with a different role_id
        User::factory()->count(20)->state(new Sequence(
            ['role_id' => 2],
            ['role_id' => 3],
            ['role_id' => 4]
        ))->create();

        //  Create 10 users and disable them
        User::factory()->count(5)->trashed()->create();
    }
}
