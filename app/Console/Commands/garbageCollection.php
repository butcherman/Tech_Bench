<?php

namespace App\Console\Commands;

use App\CustomerSystems;
use App\UserSettings;
use Exception;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Console\Output\BufferedOutput;
use Carbon\Carbon;
use App\FileLinks;
use App\User;

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
        $this->cleanupCustomerSystems();
        $this->cleanupFileLinks();
        $this->call('up');
        Log::notice('Garbage Collection Job completed');
    }

    //  Check for any failed jobs in the work queue
    protected function retryQueue()
    {
        try
        {
            Log::info('Restarting Worker Queues');
            $this->call('queue:restart');
            Log::info('Retrying failed jobs');
            $this->call('queue:retry', ['id' => 'all']);
        }
        catch(Exception $e)
        {
            report($e);
        }
    }

    //  Determine if there are any files held over in the file chunks folder
    protected function checkChunkFolder()
    {
        $files = Storage::files('chunks');

        if(count($files) > 0)
        {
            try
            {
                Log::info('Found partial files in the "chunks" folder');
                Log::info('File List - ', array($files));
                Storage::deleteDirectory('chunks');
            }
            catch(Exception $e)
            {
                report($e);
            }
        }
    }

    //  Determine if there are any files held over in the archive_downlaods folder
    protected function checkArchiveFolder()
    {
        $files = Storage::files('archive_downloads');

        if(count($files) > 0)
        {
            try
            {
                Log::info('Found files in the "archive_downloads" folder');
                Log::debug('File List - ', array($files));
                Storage::deleteDirectory('archive_downloads');
            }
            catch(Exception $e)
            {
                report($e);
            }
        }
    }

    //  Perminately remove any customer systems that were deleted
    protected function cleanupCustomerSystems()
    {
        $sysList = CustomerSystems::onlyTrashed()->get();

        if($sysList->count() > 0)
        {
            Log::info('Cleaning up Customer Systems');
            $count = $sysList->count();
            foreach($sysList as $sys)
            {
                $sys->forceDelete();
            }
            Log::info($count.' systems permanently deleted');
        }
    }

    //  Cleanup any file links that are older than 30 days
    protected function cleanupFileLinks()
    {
        //  Get the list of users that want their links cleaned up
        $users = UserSettings::where('auto_del_link', true)->with('User')->get();

        //  Cycle through the list and get any any links that are more than 30 days old
        foreach($users as $user)
        {
            $links = FileLinks::where('user_id', $user->user_id)->where('expire', '<', Carbon::now()->subDays(30)->format('Y-m-d'))->get();
            $count = $links->count();

            if($count > 0)
            {
                foreach($links as $link)
                {
                    $link->delete();
                }

                Log::info($count.' file links for '.$user->User->full_name.' deleted for being expired more than 30 days');
            }
        }
    }
}
