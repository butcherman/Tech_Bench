<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTipFiles;
use Faker\Generator as Faker;

$factory->define(TechTipFiles::class, function (Faker $faker) {
    return [
        //
        'tip_id' => factory(App\TechTips::class)->create()->tip_id,
        'file_id' => factory(App\Files::class)->create()->file_id,
    ];
});
