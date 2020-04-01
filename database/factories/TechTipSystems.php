<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TechTipSystems;

$factory->define(TechTipSystems::class, function() {
    return [
        'tip_id' => factory(App\TechTips::class)->create()->tip_id,
        'sys_id' => factory(App\SystemTypes::class)->create()->sys_id,
    ];
});
