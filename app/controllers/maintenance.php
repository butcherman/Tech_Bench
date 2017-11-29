<?php
/*
|   Maintenance operations to keep the application running smoothly
*/

class Maintenance extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('site admin');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    //  Maintenance landing page
    public function index()
    {
        $this->view('maintenance/index');
        $this->template('techUser');
        $this->render();
    }
    
    //  Show maintenance mode enable/disable page
    public function maintenanceMode()
    {
        $data = [
            'maintMode' => Template::inMaintenanceMode() ? 'checked' : ''
        ];
        
        $this->view('maintenance/maintenance_mode_form', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to toggle maintenance mode
    public function toggleMaintMode()
    {
        if(Template::inMaintenanceMode())
        {
            Template::toggleMaintMode(0);
            
            //  Note the change in the log files
            $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' turned off maintenance mode';
            Logs::writeLog('File-Link', $msg);
        }
        else
        {
            Template::toggleMaintMode(1);
            
            //  Note the change in the log files
            $msg = 'User ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']).' turned on maintenance mode';
            Logs::writeLog('File-Link', $msg);
        }
    }
    
    //  View logs landing page - show a list of all logs in the system
    public function viewLogs()
    {
        $logFiles = scanDir(__DIR__.'/../../logs/');
        
        $logList = '';
        foreach($logFiles as $file)
        {
            $parts = pathinfo($file);
            if($parts['extension'] === 'log')
            {
                $logList .= '<li class="list-group-item"><a href="/maintenance/show-log/'.$parts['filename'].'">'.$file.'</a></li>';
            } 
        }
        
        $data = [
            'logList' => $logList
        ];
        
        $this->view('maintenance/log_list', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  View a specific log file - base page
    public function showLog($logName)
    {
        $fileName = $logName.'.log';
        
        $data = [
            'logName' => $logName,
            'fileName' => $fileName
        ];
        
        $this->view('maintenance/log_show', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to load a log file
    public function loadLog($logName)
    {
        $fileName = __DIR__.'/../../logs/'.$logName.'.log';
        $output = shell_exec('tail -n50 '.$fileName);
        
        $this->render($output); 
    }
    
    //  Archive all log files into a zip and clearstatcache
    public function archiveLogs()
    {
        $today = date('Y-m-d');
        $logFiles = scanDir(__DIR__.'/../../logs/');
        
        //  Create the zip archive and add all the log files
        $zipName = __DIR__.'/../../logs/log_files-'.$today.'.zip';
        $zip = new ZipArchive;
        $zip->open($zipName, ZipArchive::CREATE);
        foreach($logFiles as $file)
        {
            $parts = pathinfo($file);
            if($parts['extension'] === 'log')
            {
                $logFile = __DIR__.'/../../logs/'.$file;
                $zip->addFile($logFile, $file);
            }
        }
        $zip->close();
        
        //  Re-cycle through the logs and delete them
        foreach($logFiles as $file)
        {
            $parts = pathinfo($file);
            if($parts['extension'] === 'log')
            {
                unlink(__DIR__.'/../../logs/'.$file);
            }
        }
    }
    
    //  File Maintenance shows all valid, unknown, and missing files for whole system (includes non category files)
    public function fileMaintenance()
    {
        $model = $this->model('reportModel');
        
        //  Determine disk space data
        $space = disk_total_space('/');
        $free = disk_free_space('/');
        $used = number_format((($space - $free) / $space) * 100, 2).'%';
        
        $countFiles = $model->compareFiles('root', 'files');
        
        //  Put together all missing files
        $missing = '';
        foreach($countFiles['missingList'] as $mis)
        {
            $missing .= '<tr><td><input type="checkbox" class="missing-item" data-filename="'.$mis.'" /></td><td>'.$mis.'</td></tr>';
        }
        
        //  Put together all unknown files 
        $unknown = '';
        foreach($countFiles['unknownList'] as $ukn)
        {
            $unknown .= '<tr><td><input type="checkbox" class="unknown-item" data-filename="'.$ukn.'" /></td><td>'.$ukn.'</td><td><span class="glyphicon glyphicon-trash"></span></td></tr>';
        }
        
        $data = [
            'numFiles'    => $model->countFiles(),
            'totalSpace'  => $space,
            'freeSpace'   => $free,
            'percent'     => $used,
            'missing'     => empty($missing) ? '<tr><td colspan=2"><h4 class="text-center">No Missing Files</h4></td></tr>' : $missing,
            'unknown'     => empty($unknown) ? '<tr><td colspan=2"><h4 class="text-center">No Unknown Files</h4></td></tr>' : $unknown
        ];
        
        $this->view('maintenance/system_files', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  File Maintenance shows all valid, unknown, and missing files for each Category
    public function categoryFileMaintenance()
    {
        $model = $this->model('reportModel');
        
        //  Determine disk space data
        $space = disk_total_space('/');
        $free = disk_free_space('/');
        $used = number_format((($space - $free) / $space) * 100, 2).'%';
        
        //  Determine file count information
        $countCust = $model->compareFiles(Config::getFile('custPath'), 'customer_files');
        $countSyst = $model->compareFiles(Config::getFile('sysPath'), 'system_files');
        $countTips = $model->compareFiles(Config::getFile('tipPath'), 'tech_tip_files');
        $countUser = $model->compareFiles(Config::getFile('userPath'), 'user_files');
        $countComp = $model->compareFiles(Config::getFile('formPath'), 'company_files');
        

        $data = [
            'numFiles'    => $model->countFiles(),
            'totalSpace'  => $space,
            'freeSpace'   => $free,
            'percent'     => $used,
            'custValid'   => $countCust['valid'],
            'custMissing' => $countCust['missing'],
            'custUnknown' => $countCust['unknown'],
            'sysValid'    => $countSyst['valid'],
            'sysMissing'  => $countSyst['missing'],
            'sysUnknown'  => $countSyst['unknown'],
            'tipValid'    => $countTips['valid'],
            'tipMissing'  => $countTips['missing'],
            'tipUnknown'  => $countTips['unknown'],
            'usrValid'    => $countUser['valid'],
            'usrMissing'  => $countUser['missing'],
            'usrUnknown'  => $countUser['unknown'],
            'compValid'   => $countComp['valid'],
            'compMissing' => $countComp['missing'],
            'compUnknown' => $countComp['unknown']
        ];
        
        $this->view('reports/system_files', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Delete a missing file from the databse
    public function deleteMissing()
    {
        $model = $this->model('files');
        $model->deleteFileByName($_POST['fileName']);
        
        $this->render('success');
    }    
    
    //  System backup home page
    public function systemBackup()
    {
        $this->view('maintenance/backup_index');
        $this->template('techUser');
        $this->render();
    }
    
    //  Run the system backup
    public function runBackup()
    {
        $model = $this->model('files');
        
        //  Set folder locations
        $folder   = 'manual_backup_'.date('mdY');
        $backRoot = __DIR__.'/../../backups/';
        $location = $backRoot.$folder.'.zip';
        
        //  Create a text file with version information
        $verFile = fopen($backRoot.'version.txt', 'w');
        $txt = "app=".VERSION."\ndb=".DBVERSION;
        fwrite($verFile, $txt);
        fclose($verFile);
        
        //  Create Database dump file
        DbBackup::runBackup();
        
        //  Create a zip folder to put all backup files into
        $zipName = $location;
        $zip = new ZipArchive;
        $zip->open($zipName, ZipArchive::CREATE);
       
        //  Place the database backup and version file into the backukp folder
        $zip->addFile($backRoot.'backup.sql', 'db_backup.sql');
        $zip->addFile($backRoot.'version.txt', 'version.txt');
        
        //  Copy all system files into the backup zip
        $source = Config::getFile('uploadRoot');
        $source = str_replace('\\', '/', realpath($source));

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', realpath($file));

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }

        //  Close the zip file and cleanup temp files
        $zip->close();
        $model->eraseFile($backRoot.'backup.sql');
        $model->eraseFile($backRoot.'version.txt');
        
        $this->render('success');
    }
    
    //  Load all existing backups
    public function loadBackups()
    {
        $backupPath = __DIR__.'/../../backups/';
        $fileList = glob($backupPath.'/*.zip', GLOB_BRACE);
        
        $actions = '<select class="backup-actions form-control"><option>Select Action</option><option value="download">Download</option><option value="delete">Delete</option></select>';
        
        $data = '';
        foreach($fileList as $file)
        {
            $fileName = basename($file);
            $modified = date('M d, Y', filemtime($file));
            
            
            $data .= '<tr><td>'.$fileName.'</td><td>'.$modified.'</td><td data-value="'.$fileName.'">'.$actions.'</td></tr>';
        }
        
        $this->render($data);
    }
    
    //  Delete an existing backup
    public function deleteBackup($fileName)
    {
        $model = $this->model('files');
        $backupPath = __DIR__.'/../../backups/';
        
        $model->eraseFile($backupPath.$fileName);
        
        $this->render('success');
    }
    
    //  Run system and database check to make sure all tables and files are in place
    public function dbCheck()
    {
        $this->view('maintenance/db_check');
        $this->template('techUser');
        $this->render();
    }
    
    //  Run the "Mysqlcheck command on the database
    public function checkDatabaseTables()
    {
        $model = $this->model('siteAdmin');
        
        $model->databaseCheck();
//        $this->render('success');
    }
    
    //  Check the system tables to make sure that all system information is ok
    public function checkSystems()
    {
        $model = $this->model('systems');
        $adminModel = $this->model('siteAdmin');
        $fileModel = $this->model('files');
        $repModel = $this->model('reportModel');
        $basePath = Config::getFile('uploadRoot').Config::getFile('sysPath');
        
        $success = '';
        //  Check the database tables and folder structure
        $catList = $model->getCategories();
        foreach($catList as $cat)
        {
            $sysList = $model->getSystems($cat->description);
            foreach($sysList as $sys)
            {
                $table = $model->getSysTable($sys->sys_id);
                //  Make sure that the data_`sysname` table exists
                if(!$cols = $model->getCols($table))
                {
                    $adminModel->basicSystemTable($table);
                    $success .= '<div>System Table Added For '.$sys->name.'</div>';
                }
                //  Make sure that the system folder exists
                if(!$fileModel->checkFolder($basePath.$cat->description.'/'.$sys->folder_location))
                {
                    $fileModel->createFolder($basePath.$cat->description.'/'.$sys->folder_location);
                    $success .= '<div>System Folder Added For '.$sys->name.'</div>';
                }
            }
        }
        
        //  Check all of the files in the database against the files in the folder
        $fileCheck = $repModel->compareFiles(Config::getFile('sysPath'), 'system_files');
        if($fileCheck['missing'] > 0)
        {
            $success .= '<div>The Following Files Cannot Be Located:';
            foreach($fileCheck['missingList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['unknown'] > 0)
        {
            $success .= '<div>The Following Files are Not Referenced In the Database:';
            foreach($fileCheck['unknownList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['missing'] > 0 || $fileCheck['unknown'] > 0)
        {
            $success .= 'Please Use File Maintenance to Correct.</div>';
        }
        
        //  If no errors, send success message
        if(empty($success))
        {
            $success = 'success';
        }
        
        $this->render($success);
    }
    
    //  Check the customers and their files
    public function checkCustomers()
    {
        $model = $this->model('customers');
        $adminModel = $this->model('siteAdmin');
        $fileModel = $this->model('files');
        $repModel = $this->model('reportModel');
        $basePath = Config::getFile('uploadRoot').Config::getFile('custPath');
        
        $success = '';
        
        //  Check the folder structure
        $custList = $model->searchCustomer();
        foreach($custList as $cust)
        {
            //  Make sure that the customer folder exists
            if(!$fileModel->checkFolder($basePath.$cust->cust_id))
            {
                $fileModel->createFolder($basePath.$cust->cust_id);
                $success .= '<div>Customer Folder Added For '.$cust->name.'</div>';
            }
        }
        
        //  Check all of the files in the database against the files in the folder
        $fileCheck = $repModel->compareFiles(Config::getFile('custPath'), 'customer_files');
        if($fileCheck['missing'] > 0)
        {
            $success .= '<div>The Following Files Cannot Be Located:';
            foreach($fileCheck['missingList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['unknown'] > 0)
        {
            $success .= '<div>The Following Files are Not Referenced In the Database:';
            foreach($fileCheck['unknownList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['missing'] > 0 || $fileCheck['unknown'] > 0)
        {
            $success .= 'Please Use File Maintenance to Correct.</div>';
        }
        
        //  If no errors, send success message
        if(empty($success))
        {
            $success = 'success';
        }
        
        $this->render($success);
    }
    
    //  Check the tech tips and their files
    public function checkTechTips()
    {
        $model = $this->model('techTips');
        $adminModel = $this->model('siteAdmin');
        $fileModel = $this->model('files');
        $repModel = $this->model('reportModel');
        $basePath = Config::getFile('uploadRoot').Config::getFile('tipPath');
        
        $success = '';
        
        //  Check the folder structure
        $tipList = $model->searchTips();
        foreach($tipList as $tip)
        {
            //  Make sure that the customer folder exists
            if(!$fileModel->checkFolder($basePath.$tip->tip_id))
            {
                $fileModel->createFolder($basePath.$tip->tip_id);
                $success .= '<div>Tip Folder Added For '.$tip->title.'</div>';
            }
        }
        
        //  Check all of the files in the database against the files in the folder
        $fileCheck = $repModel->compareFiles(Config::getFile('tipPath'), 'tech_tip_files');
        if($fileCheck['missing'] > 0)
        {
            $success .= '<div>The Following Files Cannot Be Located:';
            foreach($fileCheck['missingList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['unknown'] > 0)
        {
            $success .= '<div>The Following Files are Not Referenced In the Database:';
            foreach($fileCheck['unknownList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['missing'] > 0 || $fileCheck['unknown'] > 0)
        {
            $success .= 'Please Use File Maintenance to Correct.</div>';
        }
        
        //  If no errors, send success message
        if(empty($success))
        {
            $success = 'success';
        }
        
        $this->render($success);
    }
    
    //  Check all file links and their files 
    public function checkFileLinks()
    {
        $model = $this->model('fileLinks');
        $adminModel = $this->model('siteAdmin');
        $fileModel = $this->model('files');
        $repModel = $this->model('reportModel');
        $basePath = Config::getFile('uploadRoot').Config::getFile('uploadPath');
        
        $success = '';
        $expired = 0;
        
        //  Check the folder structure
        $linkList = $model->getLinks();
        foreach($linkList as $link)
        {
            //  Check if the file link is valid or expired
            if($model->isLinkExpired($link->link_id))
            {
                $expired++;
            }
            //  Make sure that the customer folder exists
            if(!$fileModel->checkFolder($basePath.$link->link_id))
            {
                $fileModel->createFolder($basePath.$link->link_id);
                $success .= '<div>Tip Folder Added For Link ID: '.$link->link_id.'</div>';
            }
        }
        
        if($expired > 0)
        {
            $success = '<div>'.$expired.' Links Are Expired And Should Be Deleted</div>'.$success;
        }
        
        //  Check all of the files in the database against the files in the folder
        $fileCheck = $repModel->compareFiles(Config::getFile('tipPath'), 'upload_link_files');
        if($fileCheck['missing'] > 0)
        {
            $success .= '<div>The Following Files Cannot Be Located:';
            foreach($fileCheck['missingList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['unknown'] > 0)
        {
            $success .= '<div>The Following Files are Not Referenced In the Database:';
            foreach($fileCheck['unknownList'] as $missing)
            {
                $success .= '<div>'.str_replace($basePath, 'File_Root/', $missing).'</div>';
            }
            $success .= '</div>';
        }
        if($fileCheck['missing'] > 0 || $fileCheck['unknown'] > 0)
        {
            $success .= 'Please Use File Maintenance to Correct.</div>';
        }
        
        //  If no errors, send success message
        if(empty($success))
        {
            $success = 'success';
        }
        
        $this->render($success);
    }
}
