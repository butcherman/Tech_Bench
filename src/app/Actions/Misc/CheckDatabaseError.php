<?php

namespace App\Actions\Misc;

use App\Exceptions\Database\GeneralQueryException;
use App\Exceptions\Database\RecordInUseException;
use Illuminate\Database\QueryException;

class CheckDatabaseError
{
    /**
     * Create a new class instance.
     */
    public function check(
        QueryException $e,
        ?string $message = 'Database Record In Use'
    ): void {
        if (in_array($e->errorInfo[1], [19, 1451])) {
            throw new RecordInUseException($message, 0, $e);
        } else {
            // @codeCoverageIgnoreStart
            throw new GeneralQueryException('', 0, $e);
            // @codeCoverageIgnoreEnd
        }
    }
}
