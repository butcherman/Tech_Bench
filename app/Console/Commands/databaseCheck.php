<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Files;
use App\TechTipFiles;
use App\UserSettings;
use App\CustomerFiles;
use App\FileLinkFiles;
use App\Domains\Maintenance\DatabaseCheck as MaintenanceDatabaseCheck;

class databaseCheck extends Command
{
    protected $signature = 'tb-dbcheck {--fix : Automatically fix any errors that arrise}';
    protected $fix = false;
    protected $description = 'Verify database and file structure has no errors';

    public function __construct()
    {
        parent::__construct();

    }

    public function handle()
    {
        //  Determine if fix option is enabled
        if($this->option('fix'))
        {
            $this->fix = true;
        }
        //  Extend the amount of time the script is allowed to run
        ini_set('max_execution_time', 600); //  600 seconds = 10 minutes

        //  Begin check
        $fixStr = $this->fix ? 'on' : 'off';
        $this->line('');
        $this->info('Running Database Check...');
        $this->line('');
        Log::alert('The database check has been initiated.  Fix option is set to '.$fixStr);

        $this->checkUsers();
        $this->checkForeignKeys();
        $this->verifyFilesExist();
        $this->checkForStraglerFiles();
        $this->checkForEmptyFolders();

        $this->line('');
        $this->info('Database Check Completed');
    }

    //  User check will count users and administrators and verify each user has a proper settings table
    protected function checkUsers()
    {
        $activeUsers   = User::all()->count();
        $inactiveUsers = User::onlyTrashed()->count();

        //  All users allowed to login
        $str = 'Current Active Users........................... '.$activeUsers;
        $this->line($str);
        Log::notice($str);

        //  All users that have been disabled
        $str = 'Current Inactive Users......................... '.$inactiveUsers;
        $this->line($str);
        Log::notice($str);

        //  All Active Installers
        $instArr    = [];
        $installers = User::where('role_id', 1)->get();
        foreach($installers as $ins)
        {
            $instArr[] = [
                'user_id'   => $ins->user_id,
                'username'  => $ins->username,
                'full_name' => $ins->full_name,
            ];
        }
        $this->line('');
        $this->line('Active Installers:');
        $this->table(['User ID', 'Username', 'Full Name'], $instArr);
        Log::notice('Current Active Installers - ', $instArr);

        //  All Active Administrators
        $adminArr = [];
        $administrators = User::where('role_id', 2)->get();
        foreach($administrators as $adm)
        {
            $adminArr[] = [
                'user_id'   => $adm->user_id,
                'username'  => $adm->username,
                'full_name' => $adm->full_name,
            ];
        }
        $this->line('');
        $this->line('Active Administrators:');
        $this->table(['User ID', 'Username', 'Full Name'], $adminArr);
        Log::notice('Current Active Administrators - ', $adminArr);

        //  Validate that each user has a user_settings entry
        $this->line('');
        $this->line('Validating User Settings');
        $userList = User::all();
        foreach($userList as $user)
        {
            $row = UserSettings::where('user_id', $user->user_id)->count();
            if($row != 1)
            {
                $str = 'User '.$user->full_name.' missing Settings Table';
                $this->error($str);
                Log::error($str);

                if($this->fix)
                {
                    UserSettings::create([
                        'user_id' => $user->user_id,
                    ]);
                }
            }
        }
    }

    //  Verify that all foreign keys point to valid database entries
    protected function checkForeignKeys()
    {
        $tableList = [
            'users',
            'user_role_permissions',
            'user_logins',
            'tech_tips',
            'tech_tip_systems',
            'tech_tip_files',
            'tech_tip_favs',
            'tech_tip_comments',
            'system_types',
            'system_data_fields',
            'file_links',
            'file_link_files',
            'customer_systems',
            'customer_system_data',
            'customer_notes',
            'customer_files',
            'customer_favs',
            'customer_contacts',
            'customer_contact_phones',
        ];

        $dbObj = new MaintenanceDatabaseCheck($this->fix);
        $this->line('Running Foreign Key Check');

        foreach($tableList as $table)
        {
            $valid = $dbObj->execute($table);
            if(!$valid)
            {
                $str = 'Foreign Key Check for '.$table.' failed';
                $this->error($str);
                Log::error($str);
            }
        }
    }

    //  Verify that all files listed in the files table actually exist
    protected function verifyFilesExist()
    {
        $this->line('Checking to see if all files are present');
        $fileList = Files::all();

        foreach($fileList as $file)
        {
            if(!Storage::exists($file->file_link.$file->file_name))
            {
                $str = 'File '.$file->file_link.$file->file_name.' missing';
                $this->error($str);
                Log::error($str);

                if($this->fix)
                {
                    $fileData = Files::where('file_id', $file->file_id)->with(['CustomerFiles', 'FileLinkFiles', 'TechTipFiles'])->first();
                    Log::notice('Deleted file ID '.$file->file_id.' from database as it does not exist.  File Data - ', array($fileData));

                    if(isset($fileData->CustomerFiles->cust_file_id))
                    {
                        CustomerFiles::find($fileData->CustomerFiles->cust_file_id)->delete();
                    }
                    else if(isset($fileData->FileLinkFiles->link_file_id))
                    {
                        FileLinkFiles::find($fileData->FileLinkFiles->link_file_id)->delete();
                    }
                    else if(isset($fileData->TechTipFiles->tip_file_id))
                    {
                        TechTipFiles::find($fileData->TechTipFiles->tip_file_id)->delete();
                    }

                    $fileData->delete();
                }
            }
        }
    }

    //  Check for files that exist, but are not listed in the database
    protected function checkForStraglerFiles()
    {
        //  Pull a list of all files in the local disk
        $fileList = Storage::allFiles();
        foreach($fileList as $file)
        {
            $parts = pathinfo($file);
            if($parts['dirname'] != 'chunks')
            {
                $dbData = Files::where('file_name', $parts['basename'])->first();
                if(!$dbData)
                {
                    $str = 'File '.$file.' does not have a matching database record';
                    Log::notice($str);
                    $this->error($str);
                    if($this->fix)
                    {
                        Storage::delete($file);
                        Log::alert('Deleted file - '.$file);
                    }
                }
            }
        }
    }

    //  Check the local disk to see if any of the folders are empty and can be deleted
    protected function checkForEmptyFolders()
    {
        $this->line('Checking for empty directories');
        $directories = Storage::allDirectories();

        foreach($directories as $dir)
        {
            $fileCount = count(Storage::allFiles($dir));

            // dd($fileCount);
            if($fileCount == 0)
            {
                $str = 'Directory '.$dir.' is empty and can be deleted';
                $this->error($str);
                Log::notice($str);
                if($this->fix)
                {
                    Storage::deleteDirectory($dir);
                    Log::notice('Deleted Directory '.$dir);
                }
            }
        }
    }
}
