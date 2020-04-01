<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTips;
use Faker\Generator as Faker;

$factory->define(TechTips::class, function(Faker $faker) {
    return [
        'user_id'     => factory(App\User::class)->create()->user_id,
        'public'      => 0,
        'tip_type_id' => factory(App\TechTipTypes::class)->create()->tip_type_id,
        'subject'     => $faker->sentence(4),
        'description' => $faker->paragraph(4)
    ];
});
