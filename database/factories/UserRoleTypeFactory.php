<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRoleType;
use Faker\Generator as Faker;

$factory->define(UserRoleType::class, function (Faker $faker) {
    return [
        //
        'name'        => $faker->words(2, true),
        'description' => $faker->words(3, true),
    ];
});
