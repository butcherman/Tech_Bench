<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerSystems;
use Faker\Generator as Faker;

$factory->define(CustomerSystems::class, function (Faker $faker) {
    return [
        //
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'sys_id'  => factory(App\SystemTypes::class)->create()->sys_id,
    ];
});
