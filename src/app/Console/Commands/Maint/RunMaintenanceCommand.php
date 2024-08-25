<?php

namespace App\Console\Commands\Maint;

use App\Service\Maint\CustomerMaintenanceService;
use App\Service\Maint\UserMaintenanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RunMaintenanceCommand extends Command
{
    protected $signature = 'app:maintenance {--f|fix : Automatically fix any issues}';
    protected $description = 'Check for missing data keys in DB and missing/rogue files in filesystem';

    protected $fix = false;
    protected $reportData = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fix = $this->option('fix');

        // Extend the Max Execution time for the script
        ini_set('max_execution_time', 1200);

        $this->info('Running Maintenance Command');
        $this->newLine();
        $this->userCheck();
        $this->customerCheck();


    }

    /**
     * Run User Portion of DB Check
     */
    protected function userCheck()
    {
        $userObj = new UserMaintenanceService($this->fix);

        $this->line('Installer Level Users - These users have full access to the system');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $userObj->getInstallerUsers()
        );

        $this->newLine();
        $this->line('Administrative Users - These users have some level of administrative access');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $userObj->getAdminUsers()
        );

        $this->newLine();
        $this->line('Users in Database');
        $this->table(
            ['Active Users', 'Disabled Users'],
            [[$userObj->getUserCount('active'), $userObj->getUserCount('disabled')]]
        );

        $this->newLine();
        $this->line('Checking User Profiles');

        // Check to see if any users are missing Settings Options
        $missingSettings = $userObj->verifyUserSettings();
        if ($missingSettings) {
            $this->newLine();
            $this->error('Users missing User Settings Data');
            $this->table(
                ['User ID', 'Name', 'Missing Settings ID'],
                $missingSettings,
            );

            if ($this->fix) {
                $userObj->fixUserSettings($missingSettings);
                $this->info(count($missingSettings) . ' User Profiles Fixed');
            }
        } else {
            $this->info('User Profiles OK');
        }
    }

    /**
     * Run Customer Portion of DB Check
     */
    protected function customerCheck()
    {
        $this->newLine();
        $this->line('Checking Customer Equipment for Missing Data Fields');

        $custObj = new CustomerMaintenanceService($this->fix);
        $missingDataFields = $custObj->verifyEquipmentDataFields();

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
}
