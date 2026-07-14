<?php

namespace App\Console\Commands\Customer;

use App\Jobs\File\DeleteFileDataJob;
use App\Models\FileUpload;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DataTableImportCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove any Workbook Data Table Import files older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Running Import File Clanup Job');

        $count = 0;
        $uploadList = FileUpload::where('disk', 'customers')
            ->where('folder', 'table_upload')
            ->get();

        foreach ($uploadList as $upload) {
            if ($upload->updated_at < Carbon::now()->subMinutes(5)) {
                DeleteFileDataJob::dispatch($upload);
                $count++;
            }
        }

        $this->info('Deleted '.$count.' upload files');

        Log::info('Deleted '.$count.' upload files');
    }
}
