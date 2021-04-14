<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     *  Create 10 random users with different roles
     */
    public function run()
    {
         //  Make it so that the admin password is not expired
         User::find(1)->update([
            'password_expires' => null,
        ]);

        //  Create 10 users each with a different role_id
        User::factory()->count(10)->state(new Sequence(
            ['role_id' => 1],
            ['role_id' => 2],
            ['role_id' => 3],
            ['role_id' => 4]
        ))->create();
    }
}
