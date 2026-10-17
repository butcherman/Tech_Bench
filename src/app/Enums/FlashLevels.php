<?php

namespace App\Enums;

/*
|-------------------------------------------------------------------------------
| A Flash Message must match one of the below
|-------------------------------------------------------------------------------
*/

enum FlashLevels: string
{
    case Danger = 'danger';
    case Dark = 'dark';
    case Error = 'error';
    case Help = 'help';
    case Info = 'info';
    case Light = 'light';
    case Primary = 'primary';
    case Secondary = 'secondary';
    case Success = 'success';
    case Warning = 'warning';
}
