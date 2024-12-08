<?php

namespace App\Console\Commands\Maint;

use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Services\Customer\CustomerAdministrationService;
use App\Services\Customer\CustomerEquipmentDataService;
use App\Services\File\FileMaintenanceService;
use App\Services\User\UserAdministrationService;
use App\Services\User\UserSettingsService;
use Illuminate\Console\Command;

class RunMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:maintenance {--f|fix : Automatically fix any issues}';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Check for missing data keys in DB and missing/rogue files in filesystem';

    /**
     * If problems should be fixed automatically.
     *
     * @var bool
     */
    protected $fix = false;

    protected $reportData = [];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->fix = $this->option('fix');

        // Extend the Max Execution time for the script
        ini_set('max_execution_time', 1200);

        $this->info('Running Maintenance Command');
        $this->newLine();

        // Users
        $this->userCheck();
        $this->userSettingsCheck();

        // Customers
        $this->customerCheck();
        $this->customerEquipmentCheck();

        // File System
        $fileService = $this->fileSystemCheck();
        $this->findEmptyDirectories($fileService);
        $this->findMissingFiles($fileService);
        $this->findOrphanedFiles($fileService);

        $this->newLine();
        $this->info('Database Check Complete');
        $this->line('If errors were found, run check again to clear additional errors');
    }

    /*
    |---------------------------------------------------------------------------
    | Run User Portion of DB Check
    |---------------------------------------------------------------------------
    */
    protected function userCheck(): void
    {
        $service = new UserAdministrationService;

        $this->line(
            'Installer Level Users - These users have full access to the system'
        );
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $service->getInstallerUsers()
        );

        $this->newLine();
        $this->line('Administrative Users - These users have some level of administrative access');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $service->getAdminUsers()
        );

        $this->newLine();
        $this->line('Users in Database');
        $this->table(
            ['Active Users', 'Disabled Users'],
            [
                [
                    $service->getAllUsers()->count(),
                    $service->getAllUsers(true)->count(),
                ],
            ]
        );
    }

    /*
    |---------------------------------------------------------------------------
    | User Settings Portion of DB Check
    |---------------------------------------------------------------------------
    */
    protected function userSettingsCheck(): void
    {
        $service = new UserSettingsService;

        $this->newLine();
        $this->line('Checking User Profiles');

        // Check to see if any users are missing Settings Options
        $missingSettings = $service->verifyUserSettings($this->fix);
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
        } else {
            $this->info('User Profiles OK');
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Run Customer Portion of DB Check
    |---------------------------------------------------------------------------
    */
    protected function customerCheck(): void
    {
        $service = new CustomerAdministrationService;

        // Check for customers that have no child sites
        $this->newLine();
        $this->line('Checking for Abandoned Customers');

        $lonelyCustomers = $service->verifyCustomerChildren();
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
            $this->info('Customer Check OK');
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Customer Equipment Portion of DB Check
    |---------------------------------------------------------------------------
    */
    protected function customerEquipmentCheck(): void
    {
        $service = new CustomerEquipmentDataService;

        // Check for missing data fields in all customer equipment
        $this->newLine();
        $this->line('Checking Customer Equipment for Missing Data Fields');

        $progressBar = $this->output
            ->createProgressBar(EquipmentType::all()->count());

        $missingDataFields = $service->checkAllCustomerEquipment(
            $this->fix, $progressBar
        );

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

    /*
    |---------------------------------------------------------------------------
    | Filesystem Portion of the DB Check
    |---------------------------------------------------------------------------
    */
    protected function fileSystemCheck(): FileMaintenanceService
    {
        $fileObj = new FileMaintenanceService;

        // Show Disk Stats
        $this->newLine();
        $this->line('Available Drive Space');
        $this->table(
            ['Drive Size', 'Free Space', 'Used Space', '% Used'],
            [$fileObj->getDiskSpace()],
        );

        return $fileObj;
    }

    /*
    |---------------------------------------------------------------------------
    | Find Directories without any files inside
    |---------------------------------------------------------------------------
    */
    protected function findEmptyDirectories($fileObj): void
    {
        $this->newLine();
        $this->line('Checking for empty Directories');

        $directoryList = $fileObj->getDirectoryList();
        $progressBar = $this->output
            ->createProgressBar(count($directoryList) - 2);

        $emptyDirectories = $fileObj->getEmptyDirectories(
            $directoryList,
            $this->fix,
            $progressBar
        );

        $this->newLine();

        if ($emptyDirectories) {
            $this->error(
                'The following directories are empty and can be deleted'
            );

            foreach ($emptyDirectories as $dir) {
                $this->line('     '.$dir);
            }

            if ($this->fix) {
                $this->info(
                    'Deleted '.count($emptyDirectories).' empty directories'
                );
            }
        } else {
            $this->info('No Empty Directories Found');
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Find Database Entries that do not have a file in the file system
    |---------------------------------------------------------------------------
    */
    protected function findMissingFiles($fileObj): void
    {
        $this->newLine();
        $this->line('Checking for Missing Files');

        $progressBar = $this->output->createProgressBar(FileUpload::count());
        $missingFiles = $fileObj->findMissingFiles($this->fix, $progressBar);

        $this->newLine();

        if ($missingFiles) {
            $this->error(
                'Found '.count($missingFiles).' files missing from filesystem'
            );
            $this->table(
                ['File ID', 'Disk', 'File Name'],
                $missingFiles
            );

            if ($this->fix) {
                $this->info(
                    'Deleted '.count($missingFiles).' file entries from File Uploads'
                );
            }
        } else {
            $this->info('No Missing Files');
        }
    }

    /*
    |---------------------------------------------------------------------------
    | Find Files in filesystem that do not have a database pointer
    |---------------------------------------------------------------------------
    */
    protected function findOrphanedFiles($fileObj): void
    {
        $this->newLine();
        $this->line('Checking for Orphaned Files');

        $fileList = $fileObj->getFileList();
        $progressBar = $this->output->createProgressBar(count($fileList));

        $orphanedFiles = $fileObj->findOrphanedFiles(
            $fileList,
            $this->fix,
            $progressBar
        );

        $this->newLine();

        if ($orphanedFiles) {
            $this->error('Found '.count($orphanedFiles).' Orphaned Files');
            foreach ($orphanedFiles as $orphan) {
                $this->line('     '.$orphan);
            }

            if ($this->fix) {
                $this->info('Deleted '.count($orphanedFiles).' Orphaned Files');
            }
        } else {
            $this->info('No Orphaned Files Found');
        }
    }
}
