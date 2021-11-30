<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerEquipment;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GarbageCollectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if(config('app.maintenance'))
        {
            Log::notice('Starting Garbage Collection Job');
            Artisan::call('down');

            $this->retryQueue();
            $this->checkChunkFolder();
            $this->cleanupCustomerEquipment();

            Artisan::call('up');
            Log::notice('Garbage Collection Job completed');
        }
    }

    /**
     * Retry any failed jobs in the work queue
     */
    protected function retryQueue()
    {
        try
        {
            Log::info('Retrying failed jobs');
            Artisan::call('queue:retry', ['id' => 'all']);
        }
        catch(Exception $e)
        {
            report($e);
        }
    }

    /**
     * Remove any files that have been left behind in the file chunks folder
     */
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

    /**
     * Remove any customer equipment that has been soft deleted
     */
    protected function cleanupCustomerEquipment()
    {
        $equipList = CustomerEquipment::onlyTrashed()->get();

        if($equipList->count() > 0)
        {
            Log::info('Cleaning up Customer Systems', $equipList->toArray());
            $count = $equipList->count();
            foreach($equipList as $sys)
            {
                $sys->forceDelete();
            }
            Log::info($count.' customer equipment permanently deleted');
        }
    }
}
