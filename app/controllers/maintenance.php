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
        }
        else
        {
            Template::toggleMaintMode(1);
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
}