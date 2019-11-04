<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\User::class, function(Faker $faker) {
    return [
        'username'         => $faker->unique()->lastName,
        'first_name'       => $faker->firstName,
        'last_name'        => $faker->lastName,
        'email'            => $faker->unique()->safeEmail,
        'password'         => bcrypt('password'),
        'remember_token'   => Str::random(10),
        'active'           => 1,
        'is_installer'     => 0,
        'password_expires' => null,
    ];
});
