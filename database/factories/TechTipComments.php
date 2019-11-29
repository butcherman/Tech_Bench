<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTipComments;
use Faker\Generator as Faker;

$factory->define(TechTipComments::class, function (Faker $faker) {
    return [
        //
        'tip_id'  => factory(App\TechTips::class)->create()->tip_id,
        'user_id' => factory(App\User::class)->create()->user_id,
        'comment' => $faker->words(5, true),
    ];
});
