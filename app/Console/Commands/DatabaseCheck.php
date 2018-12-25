<?php

namespace App\Console\Commands;

use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Files;
use App\User;
use App\UserSettings;
use App\UserInitialize;
use App\SystemFiles;
use App\CustomerFiles;
use App\FileLinkFiles;
use App\TechTipFiles;

class DatabaseCheck extends Command
{
    //  Command Name
    protected $signature = 'dbcheck 
                            {--fix : Automatically fix any issues that arrise} 
                            {--report : Place all results in a downloadable PDF}';

    //  Command description
    protected $description = 'Verify database and file structure matches';
    
    //  Report Data
    protected $repData, $fix, $rep;
    protected $fileArr = [];

    //  Constructor
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //  Shortcut for options being on or off
        $this->fix = $this->option('fix');
        $this->rep = $this->option('report');
        $brk = '<br />';
        
        //  Extend the Max Execution time for the script
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        
        $this->line('Running DB Check...');
        $this->line('');
        
        $this->repData = '<h3>Running DB Check...</h3>';
        
        /*****************************
        *                            *
        *  User Portion of DB Check  *
        *                            *
        ******************************/
        //  List all system administrators
        $admins = $this->getAdmins();
        $this->output($admins);
        
        //  Count both active and non active users
        $users = $this->countUsers();
        $this->output($users);
        
        //  Make sure all users have a settings table
        $settings = $this->userSettings();
        $this->output($settings);
        
        //  Check for active User Initialization links
        $links = $this->initializationLinks();
        $this->output($links);
        
        /*****************************
        *                            *
        *  File Portion of DB Check  *
        *                            *
        ******************************/
        //  Get all of the files in the file table
        $this->fileArr = $this->getFiles();
        
        //  Check for system files that are in the files table but missing actual file
        $sysFiles = $this->systemFiles();
        $this->output($sysFiles);
        
        //  Check for customer files that are in the files table but missing the actual file
        $custFiles = $this->customerFiles();
        $this->output($custFiles);
        
        //  Check for file link files that are in the files table but missing the actual file
        $linkFiles = $this->linkFiles();
        $this->output($linkFiles);
        
        //  Check for tech tip files that are in the files table but missing the actual file
        $tipFiles = $this->tipFiles();
        $this->output($tipFiles);
        
        //  For all remaining files in 'files' table - verify file exists
        $remainingFiles = $this->remainingFiles();
        $this->output($remainingFiles);
        
        //  Determine any rogue files
        $rogueFiles = $this->rogueFiles();
        $this->output($rogueFiles);

        
        
        
        
        
        
        //  Generate a PDF report if the option is selected
        if($this->rep)
        {
            $pdf = PDF::loadView('pdf.dbReport', [
                'data' => $this->repData
            ]);   
            
            $pdf->save(config('filesystems.disks.public.root').DIRECTORY_SEPARATOR.'dbReport.pdf');
        }
    }
    
    //  Print the output to the screen and write for PDF report
    private function output($data)
    {
        if(!empty($data))
        {
            foreach($data as $d)
            {
                //  Console Output
                if(!isset($d['repOnly']))
                {
                    $type = $d['type'];
                    $this->$type($d['value']);
                }

                //  Report Output
                switch($d['type'])
                {
                    case 'info':
                        $class = 'text-primary';
                        break;
                    case 'error':
                        $class = 'text-danger';
                        break;
                    default:
                        $class = 'text-dark';
                }
                $this->repData .= '<p class="'.$class.'">'.str_replace(' ', '&nbsp;', $d['value']).'</p>';
            }

            if(!isset($d['repOnly']))
            {
                $this->line('');
                $this->repData .= '<br />';
            }
        }
    }
    
    //  List all system administrators
    private function getAdmins()
    {
        $res = [];
        
        $admins = User::where('active', 1)
            ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
            ->where('user_role.role_id', '<=', 2)
            ->get();
        
        $res[] = [ 'type' => 'info', 'value' => 'System Administrators ('.count($admins).')'];
        foreach($admins as $admin)
        {
            $res[] = [
                'type' => 'line', 'value' => '     '.$admin->first_name.' '.$admin->last_name
            ];
        }
        
        return $res;
    }
    
    //  Count active and non-active users
    private function countUsers()
    {
        $res = [];
        
        $activeUsers = User::where('active', 1)->count();
        $inactiveUsr = User::where('active', 0)->count();
        
        $res[] = [
            'type'  => 'line',
            'value' => 'Active Users.................................'.$activeUsers
        ];
        $res[] = [
            'type'  => 'line',
            'value' => 'Inactive Users...............................'.$inactiveUsr
        ];
        
        return $res;
    }
    
    //  Verify all users have a settings table
    private function userSettings()
    {
        $res = [];
        
        $users    = User::all();
        $settings = UserSettings::all();
        
        $failedItems = [];
        foreach($users as $user)
        {
            $match = false;
            foreach($settings as $setting)
            {
                if($user->user_id == $setting->user_id)
                {
                    $match = true;
                    break;
                }
            }
            
            if(!$match)
            {
                $failedItems[] = [
                    'user_id' => $user->user_id,
                    'name'    => $user->first_name.' '.$user->last_name
                ];
            }
        }
        
        if($failedItems)
        {
            $res[] = [
                'type'  => 'error',
                'value' => count($failedItems).' user(s) missing settings information'
            ];
            
            foreach($failedItems as $item)
            {
                $res[] = [
                    'type' => 'line',
                    'value' => '     User ID - '.$item['user_id'].'-'.$item['name']
                ];
                
                if($this->fix)
                {
                    UserSettings::create([
                        'user_id' => $item['user_id']
                    ]);
                    
                    $res[] = [
                        'type'  => 'info',
                        'value' => '          corrected'
                    ];
                }
            }
        }
        
        return $res;
    }
    
    //  Verify User Initialization links
    private function initializationLinks()
    {
        $res = [];
        
        $initLinks = UserInitialize::all();
        
        $links = [];
        $over  = [];
        if(!empty($initLinks))
        {
            //  List each expired link
            foreach($initLinks as $link)
            {
                $links[] = [
                    'username' => $link->username,
                    'age'      => $link->created_at->diffInDays(Carbon::now())
                ];
                
                //  See if the link is more than two weeks old
                if($link->created_at->diffInDays(Carbon::now()) > 14)
                {
                    $over[] = [
                        'username' => $link->username,
                        'age'      => $link->created_at->diffInDays(Carbon::now())
                    ];
                }
            }
            
            $res[] = [
                'type' => 'line',
                'value' => count($links).' Users are awaiting to be initialized'
            ];
            
            if(!empty($over))
            {
               foreach($over as $ov)
               {
                   $res[] = [
                       'type'  => 'error',
                       'value' => '     Link for '.$ov['username'].' is '.$ov['age'].' days old'
                   ];
                   
                   if($this->fix)
                   {
                       UserInitialize::where('username', $ov['username'])->delete();
                       $res[] = [
                           'type'  => 'info',
                           'value' => '          Corrected'
                       ];
                   }
               }
            }
        }
        
        return $res;
    }
    
    //  Get all of the files in the "Files" table
    private function getFiles()
    {
        $files = Files::all();
        
        $fileArr = [];
        foreach($files as $f)
        {
            $fileArr[] = $f->file_id;
        }
        
        return $fileArr;
    }
    
    //  Check that all system files exist
    private function systemFiles()
    {
        $res = [];
        
        $badPointer  = 0;
        $missingFile = 0;
        
        $sysFiles = SystemFiles::all();
        
        $res[] = [
            'type'  => 'line',
            'value' => 'System Files......................'.count($sysFiles)
        ];
        
        //  Verify all files have a database pointer and a valid file
        foreach($sysFiles as $file)
        {
            //  Verify database pointer
            if(!in_array($file->file_id, $this->fileArr))
            {
                $badPointer++;
                $res[] = [
                    'type'    => 'error',
                    'value'   => '     File ID '.$file->file_id.' missing in files table',
                    'repOnly' => true
                ];
                
                if($this->fix)
                {
                    SystemFiles::find($file->sys_file_id)->delete();
                    $res[] = [
                        'type'    => 'info',
                        'value'   => '          Corrected',
                        'repOnly' => true
                    ];
                }
            }
            else
            {
                //  Verify that the file exists
                $fileData = Files::find($file->file_id);
                if(!Storage::exists($fileData->file_link.$fileData->file_name))
                {
                    $missingFile++;
                    $res[] = [
                        'type'    => 'error',
                        'value'   => '     Missing File ID - '.$fileData->file_id.' - '.$fileData->file_link.$fileData->file_name,
                        'repOnly' => true
                    ];

                    if($this->fix)
                    {
                        $file->delete();
                        $fileData->delete();
                        $res[] = [
                            'type'    => 'info',
                            'value'   => '          Corrected',
                            'repOnly' => true
                        ];
                    }
                }
                
                $pos = array_search($file->file_id, $this->fileArr);
                unset($this->fileArr[$pos]);
            } 
        }
        
        //  Error messages if pointer bad or file missing
        if($badPointer)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Bad file pointers............'.$badPointer
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        if($missingFile)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Missing Files................'.$missingFile
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        
        return $res;
    }
    
    //  Check that all customer files exist
    private function customerFiles()
    {
        $res = [];
        
        $badPointer  = 0;
        $missingFile = 0;
        
        $custFiles = CustomerFiles::all();
        
        $res[] = [
            'type'  => 'line',
            'value' => 'Customer Files....................'.count($custFiles)
        ];
        
        //  Verify all files have a database pointer and a valid file
        foreach($custFiles as $file)
        {
            //  Verify database pointer
            if(!in_array($file->file_id, $this->fileArr))
            {
                $badPointer++;
                $res[] = [
                    'type'    => 'error',
                    'value'   => '     File ID '.$file->file_id.' missing in files table',
                    'repOnly' => true
                ];
                
                if($this->fix)
                {
                    CustomerFiles::find($file->cust_file_id)->delete();
                    $res[] = [
                        'type'    => 'info',
                        'value'   => '          Corrected',
                        'repOnly' => true
                    ];
                }
            }
            else
            {
                //  Verify that the file exists
                $fileData = Files::find($file->file_id);
                if(!Storage::exists($fileData->file_link.$fileData->file_name))
                {
                    $missingFile++;
                    $res[] = [
                        'type'    => 'error',
                        'value'   => '     Missing File ID - '.$fileData->file_id.' - '.$fileData->file_link.$fileData->file_name,
                        'repOnly' => true
                    ];

                    if($this->fix)
                    {
                        $file->delete();
                        $fileData->delete();
                        $res[] = [
                            'type'    => 'info',
                            'value'   => '          Corrected',
                            'repOnly' => true
                        ];
                    }
                }
                $pos = array_search($file->file_id, $this->fileArr);
                unset($this->fileArr[$pos]);
            } 
        }
        
        //  Error messages if pointer bad or file missing
        if($badPointer)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Bad file pointers............'.$badPointer
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        if($missingFile)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Missing Files................'.$missingFile
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        
        return $res;
    }
    
    //  Check that all file link files exist
    private function linkFiles()
    {
        $res = [];
        
        $badPointer  = 0;
        $missingFile = 0;
        
        $linkFiles = FileLinkFiles::all();
        
        $res[] = [
            'type'  => 'line',
            'value' => 'File Link Files...................'.count($linkFiles)
        ];
        
        //  Verify all files have a database pointer and a valid file
        foreach($linkFiles as $file)
        {
            //  Verify database pointer
            if(!in_array($file->file_id, $this->fileArr))
            {
                $badPointer++;
                $res[] = [
                    'type'    => 'error',
                    'value'   => '     File ID '.$file->file_id.' missing in files table',
                    'repOnly' => true
                ];
                
                if($this->fix)
                {
                    FileLinkFiles::find($file->cust_file_id)->delete();
                    $res[] = [
                        'type'    => 'info',
                        'value'   => '          Corrected',
                        'repOnly' => true
                    ];
                }
            }
            else
            {
                //  Verify that the file exists
                $fileData = Files::find($file->file_id);
                if(!Storage::exists($fileData->file_link.$fileData->file_name))
                {
                    $missingFile++;
                    $res[] = [
                        'type'    => 'error',
                        'value'   => '     Missing File ID - '.$fileData->file_id.' - '.$fileData->file_link.$fileData->file_name,
                        'repOnly' => true
                    ];

                    if($this->fix)
                    {
                        $file->delete();
                        $fileData->delete();
                        $res[] = [
                            'type'    => 'info',
                            'value'   => '          Corrected',
                            'repOnly' => true
                        ];
                    }
                }
                $pos = array_search($file->file_id, $this->fileArr);
                unset($this->fileArr[$pos]);
            } 
        }
        
        //  Error messages if pointer bad or file missing
        if($badPointer)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Bad file pointers............'.$badPointer
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        if($missingFile)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Missing Files................'.$missingFile
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        
        return $res;
    }
    
    //  Check that all tech tip files exist
    private function tipFiles()
    {
        $res = [];
        
        $badPointer  = 0;
        $missingFile = 0;
        
        $tipFiles = TechTipFiles::all();
        
        $res[] = [
            'type'  => 'line',
            'value' => 'Tech Tip Files....................'.count($tipFiles)
        ];
        
        //  Verify all files have a database pointer and a valid file
        foreach($tipFiles as $file)
        {
            //  Verify database pointer
            if(!in_array($file->file_id, $this->fileArr))
            {
                $badPointer++;
                $res[] = [
                    'type'    => 'error',
                    'value'   => '     File ID '.$file->file_id.' missing in files table',
                    'repOnly' => true
                ];
                
                if($this->fix)
                {
                    TechTipFiles::find($file->cust_file_id)->delete();
                    $res[] = [
                        'type'    => 'info',
                        'value'   => '          Corrected',
                        'repOnly' => true
                    ];
                }
            }
            else
            {
                //  Verify that the file exists
                $fileData = Files::find($file->file_id);
                if(!Storage::exists($fileData->file_link.$fileData->file_name))
                {
                    $missingFile++;
                    $res[] = [
                        'type'    => 'error',
                        'value'   => '     Missing File ID - '.$fileData->file_id.' - '.$fileData->file_link.$fileData->file_name,
                        'repOnly' => true
                    ];

                    if($this->fix)
                    {
                        $file->delete();
                        $fileData->delete();
                        $res[] = [
                            'type'    => 'info',
                            'value'   => '          Corrected',
                            'repOnly' => true
                        ];
                    }
                }
                $pos = array_search($file->file_id, $this->fileArr);
                unset($this->fileArr[$pos]);
            } 
        }
        
        //  Error messages if pointer bad or file missing
        if($badPointer)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Bad file pointers............'.$badPointer
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        if($missingFile)
        {
            $res[] = [
                'type' => 'error',
                'value' => '     Missing Files................'.$missingFile
            ];
            if($this->fix)
            {
                $res[] = [
                    'type'  => 'info',
                    'value' => '          Corrected'
                ];
            }
        }
        
        return $res;
    }
    
    //  Check all remaining file in database that do not have pointers
    private function remainingFiles()
    {
        $res = [];
        
        $res[] = [
            'type' => count($this->fileArr) > 0 ? 'error' : 'line',
            'value' => 'Unknown Files.....................'.count($this->fileArr)
        ];
        
        if($this->fix  && count($this->fileArr) > 0)
        {
            $res[] = [
                'type' => 'info',
                'value' => '          Corrected'
            ];
            foreach($this->fileArr as $file)
            {
                $fileData = Files::find($file);
                
                if(!Storage::exists($fileData->file_link.$fileData->file_name))
                {
                    Storage::delete($fileData->file_link.$fileData->file_name);
                }
                
                $res[] = [
                    'type'    => 'info',
                    'value'   => '     File - '.$fileData->file_link.$fileData->file_name.' deleted',
                    'repOnly' => true
                ];
                
                $fileData->delete();
            }
        }
        
        return $res;
    }
    
    //  Determine any rogue files that exist but are not in the database
    private function rogueFiles()
    {
        $res = [];
        
        $allFiles = Storage::allFiles();
        $dbFiles  = Files::all();
        
        foreach($dbFiles as $key => $file)
        {
            if(in_array($file->file_link.$file->file_name, $allFiles))
            {
                $pos = array_search($file->file_link.$file->file_name, $allFiles);
                unset($allFiles[$pos]);
                unset($dbFiles[$key]);
            }
        }
        
        
        
//        print_r($allFiles);
//        print_r($dbFiles);
//        
//        $this->line(count($allFiles));
//        $this->line(count($dbFiles));
    }
}
