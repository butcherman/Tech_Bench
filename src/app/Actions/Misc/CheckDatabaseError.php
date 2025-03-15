<?php

namespace App\Actions\Misc;

use App\Exceptions\Database\DuplicateDataException;
use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use Illuminate\Database\QueryException;

class CheckDatabaseError
{
    /*
    |---------------------------------------------------------------------------
    | Examine a QueryException to see if it contains errors indicating that the
    | selected record could not be deleted because it is in use by another
    | record.  If so, throw a new RecordInUseException.
    |---------------------------------------------------------------------------
    */
    public function check(
        QueryException $e,
        ?string $message = 'Database Record In Use'
    ): void {
        match ($e->errorInfo[1]) {
            19, 1451 => throw new RecordInUseException($message, 0, $e),
            1062 => throw new DuplicateDataException($message, 0, $e),
            // @codeCoverageIgnoreStart
            default => throw new GeneralQueryException('', 0, $e)
            // @codeCoverageIgnoreEnd
        };
    }
}
