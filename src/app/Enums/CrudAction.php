<?php

namespace App\Enums;

enum CrudAction
{
    case Create;
    case Update;
    case Destroy;
    case Restore;
    case ForceDelete;
}
