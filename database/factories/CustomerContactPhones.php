<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerContactPhones;
use Faker\Generator as Faker;
use App\Model;

$factory->define(CustomerContactPhones::class, function (Faker $faker) {
    return [
        'cont_id'       => factory(App\CustomerContacts::class)->create(),
        'phone_type_id' => 1,
        'phone_number'  => 8775551212,
        'extension'     => null,
    ];
});
