<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Output\BufferedOutput;

class garbageCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'garbagecollection:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nightly maintenance script to wipe temporary and deleted data';

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
        Log::notice('Running Garbage Collection Job');
        $this->call('down');
        $this->retryQueue();
        $this->checkChunkFolder();
        $this->checkArchiveFolder();
        $this->call('up');
        Log::notice('Garbage Collection Job completed');
    }

    //  Check for any failed jobs in the work queue
    protected function retryQueue()
    {
        Log::info('Restarting Worker Queues');
        $this->call('queue:restart');
        Log::info('Retrying failed jobs');
        $this->call('queue:retry', ['id' => 'all']);
    }

    //  Determine if there are any files held over in the file chunks folder
    protected function checkChunkFolder()
    {
        $files = Storage::files('chunks');
        Log::info('Found partial files in the "chunks" folder');
        Log::info('File List - ', array($files));

        if($files)
        {
            Storage::deleteDirectory('chunks');
        }
    }

    //  Determine if there are any files held over in the archive_downlaods folder
    protected function checkArchiveFolder()
    {
        $files = Storage::files('archive_downloads');
        Log::info('Found files in the "archive_downloads" folder');
        Log::debug('File List - ', array($files));

        if($files)
        {
            Storage::deleteDirectory('archive_downloads');
        }
    }
}
