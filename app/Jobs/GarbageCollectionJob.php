<?php

namespace App\Jobs;

use Exception;
use Nwidart\Modules\Facades\Module;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\CustomerEquipment;

class GarbageCollectionJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    /**
     * Execute the job
     */
    public function handle()
    {
        if(config('app.maintenance'))
        {
            Log::notice('Starting Garbage Collection Job');
            Artisan::call('down');

            $this->retryQueue();
            $this->checkChunkFolder();
            $this->cleanupCustomerEquipment();
            $this->moduleGarbageCollection();

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

    /**
     * Run any Garbage Collection jobs for the attached modules
     */
    protected function moduleGarbageCollection()
    {
        $modules = Module::allEnabled();

        foreach($modules as $module)
        {
            if(class_exists('\\Modules\\'.$module->getName().'\\Jobs\\GarbageCollectionJob'))
            {
                $class = '\\Modules\\'.$module->getName().'\\Jobs\\GarbageCollectionJob';
                $class::dispatch();
            }
        }
    }
}