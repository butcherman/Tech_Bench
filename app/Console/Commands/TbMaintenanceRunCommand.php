<?php

namespace App\Console\Commands;

use App\Models\CustomerFile;
use App\Models\FileUploads;
use App\Models\TechTipFile;
use App\Models\User;
use App\Models\UserInitialize;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class TbMaintenanceRunCommand extends Command
{
    protected $signature = 'tb_maintenance:run
                                        {--f|fix : Automatically fix any issues}
                                        {--r|report : Create a downloadable report}';
    protected $description = 'Check for missing data keys in DB and missing/rogue files in filesystem';

    //  Report Data
    protected $fix        = false;
    protected $rep        = false;
    protected $reportData = [];
    // protected $fileArr;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fix = $this->option('fix');
        $this->rep = $this->option('report');
        $today     = Carbon::now();

        $this->info('Running Maintenance Command');
        $this->info('Taking Tech Bench offline for Maintenance');
        $this->call('down');

        //  Extend the Max Ecution time for the script as it may take a while for large databases
        ini_set('max_execution_time', 1200); //  1200 seconds = 20 minutes

        //  Initialize Report Data
        $this->repData  = "***********************************************************\n";
        $this->repData .= "*                                                         *\n";
        $this->repData .= "*               Database Report $today->year-$today->month-$today->day                *\n";
        $this->repData .= "*                                                         *\n";
        $this->repData .= "***********************************************************\n\n";

        /**
         * Run DB Check for Users
         */
        $this->userCheck();


        //  TODO - Check all customer equipment to make sure all data fileds are there
        //  TODO - Make a report on what happened


        /**
         * Run DB Check for file system
         */
        $this->fileSystemCheck();


        $this->info('Database Maintenance completed');
        $this->call('up');

        return 0;
    }

    /**
     * Run the User Portion of the DB Check
     */
    protected function userCheck()
    {
        $this->line('Checking User Database');
        $this->newLine();
        $adminList = $this->getAdmins();

        $this->line('Users with Administration Access:');
        $this->table(
            ['User ID', 'First Name', 'Last Name'],
            $adminList
        );

        //  Active and Disabled user counts
        $userData = $this->getUserCount();
        $this->line('Users in Database');
        $this->table(
            ['Active Users', 'Disabled Users'],
            [[$userData['active'], $userData['disabled']]]
        );

        // Verify all users have the correct settings
        $settingsData = $this->validateUserSettings();
        if($settingsData)
        {
            $this->error('User Settings Data Missing:');
            $this->table(
                ['User ID', 'User Name', 'Missing Setting ID'],
                $settingsData
            );

            //  If the Fix option was enabled, correct the issues
            if($this->fix)
            {
                $this->fixUserSettings($settingsData);
            }
            $this->newLine();
        }
        else
        {
            $this->info('User Settings OK');
            $this->newLine();
        }

        //  Check for old initialization links
        $openLinks = $this->checkInitializeLinks();
        if($openLinks)
        {
            $this->error('Users who have not finished setting up their accounts');
            $this->table(
                ['Username', 'Age of Link (in days)', '> 3 days old'],
                $openLinks
            );

            if($this->fix)
            {
                // purge user initialization links older than 72 hours
                UserInitialize::where('created_at', '<', Carbon::now()->subDays(3))->delete();
            }
        }

        $this->call('auth:clear-resets');

        //  Fill out report data for this function
        $this->reportData['users']['admin_list'] = $adminList;
        $this->reportData['users']['user_count'] = $userData;
        $this->reportData['users']['settings']   = $settingsData;
        $this->reportData['users']['init_links'] = $openLinks;
    }

/*****************************************************************************************************************************
 *                                                                                                                           *
 *                                                User Functions                                                             *
 *                                                                                                                           *
 *****************************************************************************************************************************/

    /**
     * List all of the users with System Administration Access
     */
    protected function getAdmins()
    {
        // Determine which permissions note 'admin' access
        $adminPermissions = UserRolePermissionTypes::where('is_admin_link', true)->get();
        //  Get all of the roles that allow at least one of these permissions
        $rolePermList = UserRolePermissions::where('allow', true)->whereIn('perm_type_id', $adminPermissions->pluck('perm_type_id'))->get()->pluck('role_id')->unique();
        $userList     = User::whereIn('role_id', $rolePermList)->get(['user_id', 'first_name', 'last_name'])->makeVisible('user_id')->makeHidden(['full_name', 'initials'])->toArray();

        return $userList;
    }

    /**
     * Get a count of active and non-active users
     */
    protected function getUserCount()
    {
        return [
            'active' => User::all()->count(),
            'disabled' => User::onlyTrashed()->get()->count(),
        ];
    }

    /**
     * Validate that all users have all available settings listed
     */
    protected function validateUserSettings()
    {
        $failed = [];

        //  Get the possible settings list
        $settings = UserSettingType::all()->pluck('setting_type_id');
        //  Get all of the user settings
        $userSettings = User::withTrashed()->with('UserSetting')->get();

        //  Compare the settings to make sure that they are all there
        foreach($userSettings as $user)
        {
            $settingsObj = $user->UserSetting->pluck('setting_type_id');
            $missing     = $settings->diff($settingsObj);

            if($missing->isNotEmpty())
            {
                $failed[] = [
                    'user_id'         => $user->user_id,
                    'full_name'       => $user->full_name,
                    'setting_type_id' => $missing->flatten(),
                ];
            }
        }

        return $failed;
    }

    /**
     * Fix any missing User Settings
     */
    protected function fixUserSettings($missingData)
    {
        $this->line('Fixing User Settings');

        foreach($missingData as $user)
        {
            foreach($user['setting_type_id'] as $m)
            {
                UserSetting::create([
                    'user_id'         => $user['user_id'],
                    'setting_type_id' => $m,
                    'value'           => true,
                ]);
            }
        }

        $this->info('User Settings Fixed');
    }

    /**
     * Check for any existing User Initialization links that may be expired
     */
    protected function checkInitializeLinks()
    {
        $initLinks = UserInitialize::all();

        if($initLinks->isNotEmpty())
        {
            $openLinks = [];
            foreach($initLinks as $link)
            {
                $openLinks[] = [
                    'username' => $link->username,
                    'age'      => $link->created_at->diffInDays(Carbon::now()),
                    'expired'  => $link->created_at->diffInHours(Carbon::now()) > 72 ? 'yes' : 'no',
                ];
            }

            return $openLinks;
        }

        return [];
    }

/*****************************************************************************************************************************
 *                                                                                                                           *
 *                                              File System Functions                                                        *
 *                                                                                                                           *
 *****************************************************************************************************************************/

    /**
     * All functions related to checking file system
     */
    protected function fileSystemCheck()
    {
        //  Simple function to convert the bytes into a readable file size
        function readableFilesize($bytes, $decimals = 2){
            $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
            $factor = floor((strlen($bytes) - 1) / 3);
            return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
        }

        $this->line('Checking Filesystem');
        $this->newLine();

        $freeSpace  = disk_free_space('/app');
        $totalSpace = disk_total_space('/app');
        $usedSpace  = $totalSpace - $freeSpace;

        $this->line('Available HDD Space');
        $this->table(
            ['HDD Size', 'Free Space', 'Used Space', '% Used'],
            [
                [
                    readableFilesize($totalSpace),
                    readableFilesize($freeSpace),
                    readableFilesize($usedSpace),
                    round(($usedSpace / $totalSpace * 100), 2).'%',
                ]
            ]
        );

        //  Find directories in the disk that have no files inside
        $emptyDir = $this->findEmptyDirectories();
        if($emptyDir)
        {
            $this->error('The following directories are empty and can be deleted');
            foreach($emptyDir as $dir)
            {
                $this->line('     '.config('filesystems.disks.local.root').DIRECTORY_SEPARATOR.$dir);
            }

            if($this->fix)
            {
                $this->fixEmptyDirectories($emptyDir);
                $this->info('Deleted empty directories');
            }
        }
        else
        {
            $this->info('No empty directories found');
        }
        $this->newLine();

        //  Find missing and extra files
        $fileData = $this->runFileMaintenance();
        if($fileData['missing'])
        {
            $this->error('The following files are missing from the file system');
            $this->table(
                ['file_id', 'disk', 'file_name'],
                $fileData['missing']
            );

            if($this->fix)
            {
                $leftOvers = $this->removeMissingPointers($fileData['missing']);
                if($leftOvers)
                {
                    $this->error('There are file pointers that may be in use by modules');
                    $this->error('You will need to run maintenance for the modules to completely remove these pointers');
                    $this->table(
                        ['file_id', 'disk', 'file_name'],
                        $leftOvers
                    );
                }
                else
                {
                    $this->info('Missing file pointers deleted');
                }
            }
        }
        else
        {
            $this->info('No missing files');
        }

        $this->newLine();
        if($fileData['extra'])
        {
            $this->error('The following files do not have database pointers and can be deleted');
            foreach($fileData['extra'] as $file)
            {
                $this->line('     '.$file);
            }

            if($this->fix)
            {
                $this->line('Deleting extra files');
                foreach($fileData['extra'] as $file)
                {
                    Storage::delete($file);
                }
                $this->line('Extra files deleted');
            }
        }
        else
        {
            $this->info('No files without Database Pointers');
        }
    }

    /**
     * Find all directories that are empty
     */
    protected function findEmptyDirectories()
    {
        $emptyDir = [];

        $directoryList = Storage::allDirectories();
        foreach($directoryList as $dir)
        {
            if(count(Storage::files($dir)) == 0 && count(Storage::directories($dir)) == 0 && $dir !== 'chunks')
            {
                $emptyDir[] = $dir;
            }
        }

        return $emptyDir;
    }

    /**
     * Delete the supplied empty directory list
     */
    protected function fixEmptyDirectories($dirList)
    {
        foreach($dirList as $dir)
        {
            Storage::deleteDirectory($dir);
        }
    }

    /**
     * Go through file_uploads table and find missing files
     * Also go through storage directory and find files that are not in database
     */
    protected function runFileMaintenance()
    {
        $fileList = Storage::disk('local')->allFiles();
        $dbList   = FileUploads::all();
        $missing  = [];

        //  Remove the .gitignore file from the file list
        if(($key = array_search('.gitignore', $fileList)) !== false) unset($fileList[$key]);

        foreach($dbList as $file)
        {
            $disk = $file->disk === 'default' ? 'local' : $file->disk;

            if(Storage::disk($disk)->missing($file->folder.DIRECTORY_SEPARATOR.$file->file_name))
            {
                $missing[] = [
                    'file_id' => $file->file_id,
                    'disk'    => $disk,
                    'path'    => Storage::disk($disk)->path($file->folder.DIRECTORY_SEPARATOR.$file->file_name),
                ];
            }
            else
            {
                $absolutePath = Storage::disk($disk)->path($file->folder.DIRECTORY_SEPARATOR.$file->file_name);
                $localPath    = str_replace(storage_path('app').DIRECTORY_SEPARATOR, '', $absolutePath);

                //  Remove the file from the file list
                if(($key = array_search($localPath, $fileList)) !== false) unset($fileList[$key]);
            }
        }

        return [
            'missing' => $missing,
            'extra'   => $fileList,
        ];
    }

    /**
     * Attempt to remove the missing file pointers from the database
     */
    protected function removeMissingPointers($pointerList)
    {
        $leftOvers = [];
        foreach($pointerList as $file)
        {
            //  Make sure that the file pointer is not in use by customers or tech tips
            $cFile = CustomerFile::withTrashed()->where('file_id', $file['file_id'])->first();
            $tFile = TechTipFile::where('file_id', $file['file_id'])->first();

            if($cFile) $cFile->forceDelete();
            if($tFile) $tFile->delete();

            try
            {
                FileUploads::find($file['file_id'])->delete();
            }
            catch(QueryException $e)
            {
                $leftOvers[] = $file;
            }
        }

        return $leftOvers;
    }
}
