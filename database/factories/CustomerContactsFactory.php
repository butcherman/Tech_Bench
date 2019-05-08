<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\CustomerContacts::class, function (Faker $faker) use ($factory) {
    return [
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'name'    => $faker->name,
        'email'   =>$faker->email
    ];
});
