<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerNotes;
use Faker\Generator as Faker;

$factory->define(CustomerNotes::class, function (Faker $faker) {
    return [
        'cust_id'     => factory(App\Customers::class)->create()->cust_id,
        'user_id'     => factory(App\User::class)->create()->user_id,
        'urgent'      => 0,
        'subject'     => $faker->sentence(5),
        'description' => $faker->paragraph(4)
    ];
});
