<?php

namespace App\Service;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use Illuminate\Database\QueryException;

class CheckDatabaseError
{
    public static function check(QueryException $e, string $message = 'Database Record In Use')
    {
        if (in_array($e->errorInfo[1], [19, 1451])) {
            throw new RecordInUseException($message, 0, $e);
        } else {
            // @codeCoverageIgnoreStart
            throw new GeneralQueryException('', 0, $e);
            // @codeCoverageIgnoreEnd
        }
    }
}
