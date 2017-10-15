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
        $this->view('maintenance.home');
        $this->template('techUser');
        $this->render();
    }
    
    //  Show maintenance mode enable/disable page
    public function maintenanceMode()
    {
        $data = [
            'maintMode' => Template::inMaintenanceMode() ? 'checked' : ''
        ];
        
        $this->view('maintenance.maintenanceMode', $data);
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
        
        $this->view('maintenance.logList', $data);
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
        
        $this->view('maintenance.showLog', $data);
        $this->template('techUser');
        $this->render();
    }
    
    //  Ajax call to load a log file
    public function loadLog($logName)
    {
        $fileName = __DIR__.'/../../logs/'.$logName.'.log';
        $output = shell_exec('tail -n50 '.$fileName);
        
        $this->render(str_replace(PHP_EOL, '<br />', $output));
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
}
