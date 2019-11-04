<?php

use App\User;
use App\UserPermissions;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //  Create the test users
        factory(App\User::class, 15)->create()->each(function($user)
        {
            $user->UserPermissions()->save(factory(App\UserPermissions::class)->make());
        });
    }
}
