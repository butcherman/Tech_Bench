<?php

namespace App\Jobs;

use App\Traits\FileTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CustomerRemoveFilesJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;
    use FileTrait;

    protected $fileList;

    /**
     * Create a new job instance
     */
    public function __construct($fileList)
    {
        $this->fileList = $fileList;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        foreach($this->fileList as $file)
        {
            $this->deleteFile($file);
        }
    }
}
