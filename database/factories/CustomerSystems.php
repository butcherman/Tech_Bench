<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerSystems;

$factory->define(CustomerSystems::class, function() {
    return [
        //
        'cust_id' => factory(App\Customers::class)->create()->cust_id,
        'sys_id'  => factory(App\SystemTypes::class)->create()->sys_id,
    ];
});
