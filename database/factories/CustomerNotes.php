<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerNotes;
use Faker\Generator as Faker;

$factory->define(CustomerNotes::class, function(Faker $faker) {
    return [
        'cust_id'     => factory(App\Customers::class),
        'user_id'     => factory(App\User::class),
        'urgent'      => 0,
        'subject'     => $faker->sentence(5),
        'description' => $faker->paragraph(4)
    ];
});
