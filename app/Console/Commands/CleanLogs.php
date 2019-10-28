<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will empty the current active log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $channel = config('logging.default');
        $this->line('');

        //  Determine if the daily or single log channel is being used
        switch ($channel) {
            case 'daily':
                $this->dailyLog();
                break;
            case 'single':
                $this->singleLog();
                break;
            default:
                $this->line('Sorry, but this command only support Single and Daily log channels.');
        }
    }

    //  Empty the current Daily log file
    private function dailyLog()
    {
        $logFile = $this->getDailyPath();

        $this->cleanLogFile($logFile);
    }

    private function getDailyPath()
    {
        //  Break up the log file name and path to get the name of the current active log file
        $logPath = config('logging.channels.daily.path');
        $fileParts = pathinfo($logPath);
        $timeStamp = Carbon::now()->format('Y-m-d');
        $logFile = $fileParts['dirname'] . DIRECTORY_SEPARATOR . $fileParts['filename'] . '-' . $timeStamp . '.' . $fileParts['extension'];

        return $logFile;
    }

    //  Empty the current single log file
    private function singleLog()
    {
        $logFile = config('logging.channels.single.path');

        $this->cleanLogFile($logFile);
    }

    private function cleanLogFile($logFile)
    {
        //  Verify the file exists before trying to clear it
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            $this->line('Log File Emptied');
        } else {
            $this->line('Well, this is embarrassing...');
            $this->line('It seems I cannot find your log file');
            $this->line('');
            $this->line('I was looking for ' . $logFile);
            $this->line('It either has not been created yet, or there was a problem with my algorithm');
        }

        $this->line('');
    }
}
