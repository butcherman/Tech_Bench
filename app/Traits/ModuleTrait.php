<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nwidart\Modules\Facades\Module;
use PragmaRX\Version\Package\Version;
use ZanySoft\Zip\Zip;

/**
 *  ModuleTrait holds all function for interacting with the Tech Bench Modules
 */
trait ModuleTrait
{
    /**
     * Compare the requirements of a module vs. what is currently installed
     */
    protected function checkRequirements($module)
    {
        $tbVersion    = (new Version)->compact();
        $requirements = $module->getRequires();
        $failed       = false;

        //  Determine if the Tech Bench is running the correct version
        if(version_compare($tbVersion, $requirements['Tech_Bench']) < 0)
        {
            $failed['version'] = [
                'tb_version' => $tbVersion,
                'requires'   => $requirements['Tech_Bench'],
            ];
        }

        //  Determine if there are other modules that also have to be loaded
        if(isset($requirements['Modules']) && count($requirements['Modules']) > 0)
        {
            foreach($requirements['Modules'] as $req)
            {
                $found = Module::find($req);
                if(!$found)
                {
                    $failed['modules'][] = $req;
                }
            }
        }

        return $failed;
    }

    /**
     * Check there requirements of a module based on the json data returned from the Tech_Bench_Modules repository
     */
    protected function checkJsonRequirements($moduleData)
    {
        $tbVersion    = (new Version)->compact();
        $failed       = false;

         //  Determine if the Tech Bench is running the correct version
         if(version_compare($tbVersion, $moduleData['min_tb_version']) < 0)
         {
             $failed['version'] = [
                 'tb_version' => $tbVersion,
                 'requires'   => $moduleData['min_tb_version'],
             ];
         }

         //  Determine if the Tech Bench is running a newer version that cannot handle the module
         if(!is_null($moduleData['max_tb_version']) && version_compare($moduleData['max_tb_version'], $tbVersion) < 1)
         {
             $failed['max_version'] = [
                'tb_version' => $tbVersion,
                'requires'   => $moduleData['max_tb_version'],
            ];
         }

         //  Determine if there are other modules that also have to be loaded
        if(isset($moduleData['modules']) && count($moduleData['modules']) > 0)
        {
            foreach($moduleData['modules'] as $req)
            {
                $found = Module::find($req);
                if(!$found)
                {
                    $failed['modules'][] = $req;
                }
            }
        }

        return $failed;
    }

    /**
     * Run database migrations for a specific module
     */
    protected function runMigrations($module)
    {
        Artisan::call('module:migrate', ['module' => $module->getStudlyName(), '--force' => true]);
    }

    /**
     * Rollback the migrations for a sepcifiec=d module
     */
    protected function rollbackMigrations($module)
    {
        Artisan::call('module:migrate-rollback', ['module' => $module->getStudlyName(), '--force' => true]);
    }

    /**
     * Delete a disk associated with a Module
     */
    protected function destroyModuleDisk($module)
    {
        $disks = config($module->getLowerName().'.disks');

        if(is_array($disks))
        {
            foreach($disks as $disk)
            {
                Log::debug('Deleting Disk for Module '.$module->getName(), $disk);
                File::deleteDirectory($disk['root']);
            }
        }
    }

    /**
     * Install all dependency packages for Composer and NPM
     */
    protected function installDependencies($module)
    {
        //  Install Composer and NPM packages
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && composer install --no-dev --no-interaction --optimize-autoloader');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && npm install --silent --only=production');

        $this->runProduction();
    }

    /**
     * Run the NPM run production command to build all Javascript files
     */
    protected function runProduction()
    {
        //  Combine the new Javascript files
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.' && npm run production');
    }

    /**
     * Check the butcherman/tech_bench_modules GitHub repository for a list of available modules
     */
    protected function getAvailableModules()
    {
        // $response = Http::get('https://raw.githubusercontent.com/butcherman/Tech_Bench_Modules/main/module_list.json')->collect();
        // return $response;

        return [
            "FileLinkModule" => [
                "module_name"     => "File Link Module",
                "alias"           => "FileLinkModule",
                "module_version"  => 1.0,
                "min_tb_version"  => 6.0,
                "max_tb_version"  => null,
                "download_link"   => "https://github.com/butcherman/FileLinkModule/archive/refs/tags/1.0.0.zip",
                "description"     => "This module is an extension to the Tech Bench application. It allows users to create file links that will allow guests to either upload files for the user to access, or download files that the user has loaded to the link. Each link has a unique URL to access it, as well as a set expiration date. Once that date has passed, the files in the link are no longer accessible by guest access.  \n By using this feature, users can securely pass files to customers that may be too large for emailing or other means."
            ],
            "TestModule1" => [
                "module_name"     => "TestModule1",
                "alias"           => "TestModule1",
                "module_version"  => 1.0,
                "min_tb_version"  => 5.0,
                "max_tb_version"  => 5.9,
                "download_link"   => "https://github.com/butcherman/FileLinkModule/archive/refs/tags/1.0.0.zip",
                "description"     => "This module is for testing purposes only"
            ],
            "TestModule2" => [
                "module_name"     => "TestModule2",
                "alias"           => "TestModule2",
                "module_version"  => 1.0,
                "min_tb_version"  => 6.3,
                "max_tb_version"  => null,
                "download_link"   => "https://github.com/butcherman/FileLinkModule/archive/refs/tags/1.0.0.zip",
                "description"     => "This module is for testing purposes only",
                "modules"         => ['FileLinkModule']
            ]
        ];
    }

    /**
     * Download a Module from the supplied repository
     */
    protected function downloadModule($moduleData)
    {
        $download = Http::get($moduleData['download_link']);
        Storage::disk('modules')->put($zipName = $moduleData['alias'].'_package.zip', $download);

        return $zipName;
    }

    /**
     * Decompress (unzip) a packaged module
     */
    protected function openModulePackage($packageName)
    {
        $root = config('filesystems.disks.modules.root');
        $archive = Zip::open($root.DIRECTORY_SEPARATOR.$packageName);
        $archive->extract($root.DIRECTORY_SEPARATOR.'tmp');
        $archive->close();
    }

    /**
     * Remove the Temporary folder along with the zipped package
     */
    protected function cleanupPackage($packageName)
    {
        Storage::disk('modules')->deleteDirectory('tmp');
        Storage::disk('modules')->delete($packageName);
    }

    /**
     * Install the module files from the 'tmp' directory
     */
    protected function installModule($moduleData)
    {
        $directoryName = Storage::disk('modules')->directories('tmp')[0];
        $fileList      = Storage::disk('modules')->allFiles($directoryName);

        foreach($fileList as $file)
        {
            $rename = str_replace($directoryName, '', $file);

            Storage::disk('modules')->move($file, $moduleData['alias'].DIRECTORY_SEPARATOR.$rename);
        }
    }
}
