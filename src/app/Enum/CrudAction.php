<?php

namespace App\Enum;

/**
 * Crud Actions are standard Create, Read, Update, Delete operations for DB
 */
enum CrudAction
{
    case Create;
    case Update;
    case Destroy;
    case Restore;
    case ForceDelete;
}
