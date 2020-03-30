<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\User::class, function(Faker $faker) {
    return [
        'role_id'          => 4,
        'username'         => $faker->unique()->lastName,
        'first_name'       => $faker->firstName,
        'last_name'        => $faker->lastName,
        'email'            => $faker->unique()->safeEmail,
        'password'         => bcrypt('password'),  //  All test users will have the password of 'password' to allow testing access
        'remember_token'   => Str::random(10),
        'password_expires' => null,
    ];
});
