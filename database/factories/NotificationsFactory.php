<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
    ];
});
