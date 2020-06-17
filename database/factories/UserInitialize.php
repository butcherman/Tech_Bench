<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\UserInitialize::class, function() {
    return [
        'username' => factory(App\User::class),
        'token'    => strtolower(Str::random(30)),
    ];
});
