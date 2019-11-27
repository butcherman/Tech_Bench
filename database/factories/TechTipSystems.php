<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        //
        'tip_id' => factory(App\TechTips::class)->create()->tip_id,
        'sys_id' => factory(App\SystemTypes::class)->create()->sys_id,
    ];
});
