<?php

use App\User;
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
        //  Create 25 test users  3 will be admin, 2 will be report users, the remainder will be basic tech users
        factory(User::class, 3)->create([
            'role_id' => 2,
        ]);
        factory(User::class, 2)->create([
            'role_id' => 3,
        ]);
        factory(User::class, 20)->create();
    }
}
