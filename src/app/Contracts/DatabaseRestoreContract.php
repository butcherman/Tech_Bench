<?php

namespace App\Contracts;

interface DatabaseRestoreContract
{
    public function restore(string $backupPath): void;
}
