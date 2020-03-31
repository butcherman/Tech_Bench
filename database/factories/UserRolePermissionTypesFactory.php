<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\UserRolePermissionTypes;

$factory->define(UserRolePermissionTypes::class, function (Faker $faker) {
    return [
        'description' => $faker->words(2, true),
    ];
});
