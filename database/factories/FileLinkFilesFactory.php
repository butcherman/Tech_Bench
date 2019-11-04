<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

$factory->define(App\FileLinkFiles::class, function() {
    return [
        'link_id'  => factory(App\FileLinks::class)->create()->link_id,
        'file_id'  => factory(App\Files::class)->create()->file_id,
        'user_id'  => factory(App\User::class)->create()->user_id,
        'added_by' => null,
        'upload'   => 0
    ];
});
