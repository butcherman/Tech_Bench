<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\CustomerNotes::class, function (Faker $faker) {
    return [
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'user_id' => 1,
        'urgent'  => 0,
        'subject' => 'This is a test Note',
        'description' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac vulputate lorem. Fusce ornare ornare consequat. Donec ac rutrum massa. Nulla accumsan dolor eget neque finibus, id feugiat mi luctus. Phasellus sodales neque feugiat, efficitur urna sed, condimentum est. Aliquam venenatis purus finibus arcu elementum sodales.</p>'
    ];
});
