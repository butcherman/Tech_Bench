<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */


use App\FileLinkFiles;

$factory->define(FileLinkFiles::class, function() {
    return [
        'link_id'  => factory(App\FileLinks::class),
        'file_id'  => factory(App\Files::class),
        'user_id'  => factory(App\User::class),
        'added_by' => null,
        'upload'   => 0,
        'note'     => null,
    ];
});
