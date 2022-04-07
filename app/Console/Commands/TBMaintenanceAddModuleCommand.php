<?php

namespace App\Console\Commands;

use ZanySoft\Zip\Zip;

use Illuminate\Console\Command;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class TBMaintenanceAddModuleCommand extends Command
{
    protected $signature = 'tb_maintenance:module_add';
    protected $description = 'Gives the ability to download and install a Tech Bench Module';

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
        $this->line('Checking for available Tech Bench Modules');

        //  Get the list of available modules from the Tech Bench Modules repository
        $response = Http::get('https://raw.githubusercontent.com/butcherman/Tech_Bench_Modules/main/module_list.json')->collect();

        $modList = [];
        foreach($response as $mod)
        {
            $modList[] = $mod['module_name'];
        }

        //  Request which module to install
        $module = $this->choice('Select Which Module to Add', $modList);

        //  Each key of the json file is the alias name (module name in Studly Case)
        $alias = Str::studly($module);
        $moduleData = $response[$alias];

        //  Download the module
        $this->info('Downloading '.$moduleData['module_name']);
        $download = Http::get($moduleData['download_link']);
        Storage::disk('modules')->put($zipName = $moduleData['alias'].'_package.zip', $download);

        //  Unzip module package
        $this->info('Module Downloaded');
        $this->info('Unpacking');

        $root = config('filesystems.disks.modules.root');
        $archive = Zip::open($root.DIRECTORY_SEPARATOR.$zipName);
        $archive->extract($root.DIRECTORY_SEPARATOR.'tmp');
        $archive->close();

        //  Move data into Module folder
        $directoryName = Storage::disk('modules')->directories('tmp')[0];
        $fileList      = Storage::disk('modules')->allFiles($directoryName);

        foreach($fileList as $file)
        {
            $rename = str_replace($directoryName, '', $file);

            Storage::disk('modules')->move($file, $moduleData['alias'].DIRECTORY_SEPARATOR.$rename);
        }

        //  Activate the module
        $this->info('Activating Module');
        Artisan::call('tb_module:activate '.$moduleData['alias']);

        //  Remove temporary files
        $this->info('Cleaning Up');

        Storage::disk('modules')->deleteDirectory('tmp');
        Storage::disk('modules')->delete($zipName);

        //  Finished
        $this->info($moduleData['module_name'].' has successfully been installed');
        $this->info('Note:  Users may need to close all browsers and re-open for the module to work properly');

        return Command::SUCCESS;
    }
}
