<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerContacts;
use Faker\Generator as Faker;

$factory->define(CustomerContacts::class, function(Faker $faker) {
    return [
        'cust_id' => factory(App\Customers::class)->create(),
        'name'    => $faker->name(),
        'email'   => $faker->email(),
    ];
});
