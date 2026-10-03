<?php

namespace App\Services\Maintenance;

use App\Contracts\DatabaseRestoreContract;
use App\Exceptions\Maintenance\RestoreFailedException;

/**
 * @codeCoverageIgnore
 */
class MySqlRestoreProcess implements DatabaseRestoreContract
{
    public function restore(string $backupPath): void
    {
        $config = config('database.connections.mysql');

        $command = sprintf(
            'mysql --host=%s --port=%s --user=%s --password=%s %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['port']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
        );

        $process = proc_open(
            $command,
            [
                0 => ['file', $backupPath, 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
        );

        if (! is_resource($process)) {
            throw new RestoreFailedException(
                'Unable to start MySQL restore process.'
            );
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0) {
            throw new RestoreFailedException(
                'MySQL restore failed: '.$stderr
            );
        }
    }
}
