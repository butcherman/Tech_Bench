<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\UserInitialize::class, function (Faker $faker) {
    return [
        'username' => factory(App\User::class)->create()->username,
        'token' => strtolower(Str::random(30)),
    ];
});
