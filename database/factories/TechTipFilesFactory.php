<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTipFiles;

$factory->define(TechTipFiles::class, function() {
    return [
        'tip_id'  => factory(App\TechTips::class)->create()->tip_id,
        'file_id' => factory(App\Files::class)->create()->file_id,
    ];
});
