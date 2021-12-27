<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TbBackupDefaultCommand extends Command
{
    protected $signature   = 'tb_backup:default {--confirmed} {--demo}';
    protected $description = 'Completely wipe all Tech Bench data and start from scratch';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     */
    public function handle()
    {
        $this->warn(' ___________________________________________________________________ ');
        $this->warn('|                      IMPORTANT NOTE:                              |');
        $this->warn('|               ALL EXISTING DATA WILL BE ERASED                    |');
        $this->warn('|                   THIS CANNOT BE UNDONE!!!                        |');
        $this->warn('|___________________________________________________________________|');
        $this->warn('                                                                     ');

        if(!$this->option('confirmed') && !$this->confirm('Are you sure?'))
        {
            $this->line('Operation Canceled');
            return 0;
        }

        $this->call('down');
        $this->callSilently('migrate:fresh');
        $this->wipeFiles();
        if($this->option('demo'))
        {
            $this->line('Creating demo data');
            $this->callSilently('db:seed');
        }
        $this->callSilently('storage:link');

        $this->info('Operation complete');
        $this->call('up');
        return 0;
    }

    protected function wipeFiles()
    {
        $disk = 'local';

        //  Clear all files from the disk
        $files = Storage::disk($disk)->allFiles();

        foreach($files as $file)
        {
            if($file != '.gitignore')
            {
                Storage::disk($disk)->delete($file);
            }
        }

        //  Clear all sub directories from the disk
        $folders = Storage::disk($disk)->directories();
        foreach($folders as $folder)
        {
            Storage::disk($disk)->deleteDirectory($folder);
        }

        //  Restore the public directory
        Storage::disk('public')->put('.gitignore', '*');
        Storage::disk('public')->append('.gitignore', '!.gitignore');
        Storage::disk('public')->append('.gitignore', '');
    }
}
