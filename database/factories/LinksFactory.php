<?php

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\FileLinks::class, function (Faker $faker) {
    return [
        'user_id'      => 1,
        'cust_id'      => null,
        'link_hash'    => Str::random(10),
        'link_name'    => implode(' ', /** @scrutinizer ignore-type */ $faker->words(3)),
        'note'         => null,
        'expire'       => date('Y-m-d', strtotime('+30 days')),
        'allow_upload' => 1
    ];
});
