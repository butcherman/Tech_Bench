<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class backupList extends Command
{
    protected $signature = 'tb-backup:list';
    protected $description = 'List all of the available backups';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->line('Showing all available backups');
        $this->line('');
        $this->line('Name              Date     Time');
        $this->line('-------------------------------------');
        $fileList = Storage::disk('backup')->files();
        foreach($fileList as $file)
        {
            if($file != '.gitignore')
            {
                $this->info($file);
            }
        }
    }
}
