<?php

namespace App\Observers;

abstract class Observer
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            request()->user() !== null => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }
}
