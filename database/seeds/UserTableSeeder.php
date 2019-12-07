<?php

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
        //  Create the test users - note, none are installers - permissions are assigned randomly
        // factory(App\User::class, 15)->create()->each(function($user)
        // {
        //     // $user->UserPermissions()->save(factory(App\UserPermissions::class)->create(['user_id' => $user->user_id]));
        // });
    }
}
