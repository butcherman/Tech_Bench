<?php

namespace App\Enum;

enum CrudAction
{
    case Create;
    case Update;
    case Destroy;
    case Restore;
    case ForceDelete;
}