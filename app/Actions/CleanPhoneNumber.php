<?php

namespace App\Actions;

class CleanPhoneNumber
{
    public static function process(string $number): string
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}
