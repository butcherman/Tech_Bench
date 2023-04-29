<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;
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
 *
 * @codeCoverageIgnore
 */
trait ModuleTrait
{
    /**
     * Compare the requirements of a module vs. what is currently installed
     */
    protected function checkRequirements($module)
    {
        $tbVersion = (new Version)->compact();
        $requirements = $module->getRequires();
        $failed = false;

        //  Determine if the Tech Bench is running the correct version
        if (version_compare($tbVersion, $requirements['Tech_Bench']) < 0) {
            $failed['version'] = [
                'tb_version' => $tbVersion,
                'requires' => $requirements['Tech_Bench'],
            ];
        }

        //  Determine if there are other modules that also have to be loaded
        if (isset($requirements['Modules']) && count($requirements['Modules']) > 0) {
            foreach ($requirements['Modules'] as $req) {
                $found = Module::find($req);
                if (! $found) {
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
        $tbVersion = (new Version)->compact();
        $failed = false;

        //  Determine if the Tech Bench is running the correct version
        if (version_compare($tbVersion, $moduleData['min_tb_version']) < 0) {
            $failed['version'] = [
                'tb_version' => $tbVersion,
                'requires' => $moduleData['min_tb_version'],
            ];
        }

        //  Determine if the Tech Bench is running a newer version that cannot handle the module
        if (! is_null($moduleData['max_tb_version']) && version_compare($moduleData['max_tb_version'], $tbVersion) < 1) {
            $failed['max_version'] = [
                'tb_version' => $tbVersion,
                'requires' => $moduleData['max_tb_version'],
            ];
        }

        //  Determine if there are other modules that also have to be loaded
        if (isset($moduleData['modules']) && count($moduleData['modules']) > 0) {
            foreach ($moduleData['modules'] as $req) {
                $found = Module::find($req);
                if (! $found) {
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

        if (is_array($disks)) {
            foreach ($disks as $disk) {
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
        $response = Http::get('https://raw.githubusercontent.com/butcherman/Tech_Bench_Modules/main/module_list.json')->collect();

        return $response;
    }

    /**
     * Compare versions to see which modules can be updated to a newer version
     */
    protected function checkForUpdates($activeModules)
    {
        $moduleList = $this->getAvailableModules();
        $updateList = [];

        foreach ($activeModules as $module) {
            $findModule = $moduleList[$module->getName()];
            if (empty($findModule)) {
                Log::notice('Module '.$module['module_name'].' is active but not on the Tech Bench Modules Repository');

                continue;
            }

            $updateList[] = [
                'module_name' => $findModule['module_name'],
                'alias' => $module->getName(),
                'current_ver' => config($module->getLowerName().'.ver'),
                'available_ver' => $findModule['module_version'],
                'download_link' => $findModule['download_link'],
                'can_update' => version_compare($findModule['module_version'], config($module->getLowerName().'.ver')) < 1 ? false : true,
            ];
        }

        return collect($updateList);
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
        $fileList = Storage::disk('modules')->allFiles($directoryName);

        foreach ($fileList as $file) {
            $rename = str_replace($directoryName, '', $file);

            //  If the file already exists, delete it so we can place the new file there
            if (Storage::disk('modules')->exists($moduleData['alias'].DIRECTORY_SEPARATOR.$rename)) {
                Storage::disk('modules')->delete($moduleData['alias'].DIRECTORY_SEPARATOR.$rename);
            }

            Storage::disk('modules')->move($file, $moduleData['alias'].DIRECTORY_SEPARATOR.$rename);
        }
    }

    /**
     * Updated all cached data
     */
    protected function updateCache()
    {
        //  We will only cache if running in production
        if (App::environment(['production'])) {
            Artisan::call('config:cache');
            Artisan::call('route:clear');
            Artisan::call('breadcrumbs:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        }
    }
}
