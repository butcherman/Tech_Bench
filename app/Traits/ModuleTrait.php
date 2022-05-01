<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Nwidart\Modules\Facades\Module;
use PragmaRX\Version\Package\Version;

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
     * Run database migrations for a specific module
     */
    protected function runMigrations($module)
    {
        Artisan::call('module:migrate', ['module' => $module->getStudlyName(), '--force' => true]);
    }

    /**
     * Install all dependency packages for Composer and NPM
     */
    protected function installDependencies($module)
    {
        //  Install Composer and NPM packages
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && composer install --no-dev --no-interaction --optimize-autoloader');
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR.$module->getStudlyName().' && npm install --silent --only=production');

        //  Combine the new Javascript files
        shell_exec('cd '.base_path().DIRECTORY_SEPARATOR.' && npm run production');
    }
}
