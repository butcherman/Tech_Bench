<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserRolePermissionTypes;
use Faker\Generator as Faker;

$factory->define(UserRolePermissionTypes::class, function (Faker $faker) {
    return [
        //
        'description' => $faker->words(2, true),
    ];
});
