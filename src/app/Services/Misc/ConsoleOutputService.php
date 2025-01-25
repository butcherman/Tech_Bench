<?php

namespace App\Services\Misc;

use App\Events\Admin\AdministrationEvent;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputService extends ConsoleOutput
{
    /**
     * Overwrite the writeln function to broadcast an event with the message
     * This can stream console output to the browser via websocket
     */
    public function writeln(string|iterable $messages, int $options = self::OUTPUT_NORMAL): void
    {
        event(new AdministrationEvent(preg_replace('/(<[^>]*>)(.*)(<[^>]*>)/', '$2', $messages)));
        $this->write($messages, true, $options);
    }
}
