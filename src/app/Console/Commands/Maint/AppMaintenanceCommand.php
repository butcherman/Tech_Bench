<?php

namespace App\Console\Commands\Maint;

use App\Services\Customer\CustomerAdministrationService;
use App\Services\Customer\CustomerEquipmentDataService;
use App\Services\Maintenance\FileMaintenanceService;
use App\Services\User\UserAdministrationService;
use App\Services\User\UserSettingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\spin;

class AppMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:maintenance {--f|fix : Automatically fix any issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for missing data keys in DB and missing/rogue files in filesystem';

    /**
     * Flag to note if issues should be fixed automatically.
     *
     * @var bool
     */
    protected $fix = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::notice('app:maintenance command called');

        $this->fix = $this->option('fix');

        // Extend the Max Execution time for the script
        ini_set('max_execution_time', 1200);

        $this->info('Running Maintenance Command');
        $this->newLine();

        $this->userCheck();
        $this->customerCheck();
        $this->customerEquipmentCheck();
        $this->fileSystemCheck();

        $this->newLine();
        $this->info('Database Check Complete');
        $this->line('If errors were found, run check again to clear additional errors');

        Log::info('app:maintenance command completed');
    }

    /**
     * List Installer and Administration level users for review. List count of
     * active and disabled users for review. Check all users to ensure that
     * they have all of the necessary settings entries in the database.
     */
    protected function userCheck(): void
    {
        $svc = new UserAdministrationService;

        /**
         * List Installer and Administration Users
         */
        $this->line('Installer Level Users - These users have full access to the system');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $svc->getInstallerUsers()
        );

        $this->newLine();
        $this->line('Administrative Users - These users have some level of administrative access');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $svc->getAdminUsers()
        );

        /**
         * List Active and Disabled Users
         */
        $this->newLine();
        $this->line('Users in Database');
        $this->table(
            ['Active Users', 'Disabled Users'],
            [[$svc->getAllUsers()->count(), $svc->getAllUsers(true)->count()]]
        );

        /**
         * Check user Settings for missing entries
         */
        $this->newLine();
        $this->line('Checking User Profiles');

        $settingsSvc = new UserSettingsService;

        $missingSettings = spin(
            callback: fn () => $settingsSvc->verifyUserSettings($this->fix)
        );
        $this->newLine();

        if ($missingSettings) {
            $this->newLine();
            $this->error('Users missing User Settings Data');
            $this->table(
                ['User ID', 'Name', 'Missing Settings ID'],
                $missingSettings,
            );

            if ($this->fix) {
                $this->info(count($missingSettings).' User Profiles Fixed');
            }

            return;
        }

        $this->info('User Profiles OK');
    }

    /**
     * Check each customer profile to make sure that they have at least one
     * site attached.  Fix flag will delete customers with no sites attached.
     */
    protected function customerCheck(): void
    {
        $svc = new CustomerAdministrationService;

        // Check for customers that have no child sites
        $this->newLine();
        $this->line('Checking for Abandoned Customers');

        $lonelyCustomers = $svc->verifyCustomerChildren($this->fix);
        $this->newLine();

        if ($lonelyCustomers) {
            $this->error(
                'Found '.count($lonelyCustomers).' Customers without a site attached'
            );
            $this->table(
                ['Customer ID', 'Customer Name'],
                $lonelyCustomers,
            );

            if ($this->fix) {
                $this->info(
                    'Deleted '.count($lonelyCustomers).' Customer Profiles'
                );
            }
        } else {
            $this->info('Customer Site Check OK');
        }
    }

    protected function customerEquipmentCheck(): void
    {
        $service = new CustomerEquipmentDataService;

        // Check for missing data fields in all customer equipment
        $this->newLine();
        $this->line('Checking Customer Equipment for Missing Data Fields');

        $missingDataFields = $service->checkAllCustomerEquipment($this->fix);

        $this->newLine();

        if ($missingDataFields) {
            $this->error('Customer Equipment Data Fields Missing');
            $this->table(
                ['Customer Equipment ID', 'Missing Data Field ID'],
                $missingDataFields
            );

            if ($this->fix) {
                $this->info('Added missing Customer Equipment Data Fields');
            }
        } else {
            $this->info('No missing Customer Equipment Data Fields');
        }
    }

    /**
     * Filesystem Check. Report on used/free storage space. Get a list of empty
     * directories, files that are noted in the database but are not in the
     * filesystem, and files that are in the filesystem with no db pointer.
     */
    protected function fileSystemCheck(): void
    {
        $svc = new FileMaintenanceService;

        /**
         * Show Disk Stats
         */
        $this->newLine();
        $this->line('Storage Space');
        $this->table(
            ['Free Space', 'Used Space'],
            [[
                $svc->getDiskFreeSpace('local', true),
                $svc->getStorageDiskSize('local', true),
            ]],
        );

        /**
         * Show directories with no files in them.
         */
        $this->newLine();
        $emptyDirectories = spin(
            message: 'Checking for empty directories',
            callback: fn () => $svc->getEmptyDirectories(Storage::path(''))
        );

        if ($emptyDirectories) {
            $this->error('The following directories are empty and can be deleted');
            foreach ($emptyDirectories as $dir) {
                $this->line('    '.$dir);
            }

            if ($this->fix) {
                foreach ($emptyDirectories as $dir) {
                    File::deleteDirectory($dir);
                }
                $this->info('Deleted '.count($emptyDirectories).' empty directories');
            }
            $this->newLine();
        }

        $this->line('Empty Directory check completed');

        /**
         * Show files that have a database pointer, but are not in filesystem.
         */
        $this->newLine();
        $missingFiles = spin(
            message: 'Checking for missing files',
            callback: fn () => $svc->getMissingFiles()
        );

        if (count($missingFiles)) {
            $this->error('Found '.count($missingFiles).' files missing from filesystem.');
            $this->table(
                ['File ID', 'Disk', 'File Name'],
                $missingFiles->makeVisible('disk')
                    ->makeHidden(['file_size', 'href', 'created_stamp'])
                    ->toArray()
            );

            if ($this->fix) {
                foreach ($missingFiles as $file) {
                    $svc->forceDeleteFileUpload($file);
                }

                $this->info('Deleted '.count($missingFiles).' missing file entries');
            }

            $this->newLine();
        }

        $this->line('Missing File check completed');

        /**
         * Show files that exist in the filesystem, but do not have a database
         * entry.
         */
        $this->newLine();
        $orphanedFiles = spin(
            message: 'Checking for Orphaned Files',
            callback: fn () => $svc->getOrphanedFiles()
        );

        if (count($orphanedFiles)) {
            $this->error('Found '.count($orphanedFiles).' files without a database entry');
            foreach ($orphanedFiles as $file) {
                $this->line('    '.str_replace('/app/storage/app/', '', $file));
            }

            if ($this->fix) {
                foreach ($orphanedFiles as $file) {
                    File::delete($file);
                }

                $this->info('Deleted '.count($orphanedFiles).' orphaned files.');
            }

            $this->newLine();
        }
    }
}
