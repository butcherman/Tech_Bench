<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class backupList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tb-backup:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all of the available backups';

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
