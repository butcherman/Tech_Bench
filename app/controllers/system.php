<?php
/*
|   System controller allows user to access documents and files for each system type in the databse
*/
class System extends Controller
{
    public function __construct()
    {
        Security::setPageLevel('tech');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
        }
    }
    
    //  Index function will determine the category and system type being called and display the information for that system
    public function index($cat = '', $sys = '')
    {
        if(!empty($cat) && !empty($sys))
        {
            $this->showSystemData($cat, $sys);
        }
        else if(!empty($cat) && empty($sys))
        {
            $this->showSystems($cat);
        }
        else
        {
            $this->showCategories();
        }
        
        $this->template('techUser');
        $this->render();
    }
    
    //  Show categories function will list all of the available categories 
    private function showCategories()
    {
        $model = $this->model('systems');
        $catList = $model->getCategories();
        
        $data['header'] = 'Select A System Category';
        $data['list'] = '';
        
        foreach($catList as $cat)
        {
            $data['list'] .= '<li class="list-group-item"><a href="/system/'.str_replace(' ', '-', $cat->description).'" class="btn btn-default btn-block">'.strtoupper($cat->description).'</a></li>';
        }
        
        $this->template('techUser');
        $this->view('system.systemList', $data);
    }
    
    //  Show the system types for a specific category
    private function showSystems($cat)
    {
        $model = $this->model('systems');
        $sysList = $model->getSystems($cat);
        
        $data['header'] = 'Select A System';
        $data['list'] = '';
        
        foreach($sysList as $sys)
        {
            $data['list'] .= '<li class="list-group-item"><a href="/system/'.str_replace(' ', '-', $cat).'/'.str_replace(' ', '-', $sys->name).'" class="btn btn-default btn-block">'.strtoupper($sys->name).'</a></li>';
        }
        
        $this->template('techUser');
        $this->view('system.systemList', $data);
    }
    
    //  Show system information for the selected system
    private function showSystemData($cat, $sys)
    {
        $model = $this->model('systems');
        
        //  Verify that the system is valid
        if(!($sysID = $model->getSysID(str_replace('-', ' ', $sys))))
        {
            $msg = 'User: '.$_SESSION['id'].' attempted to access an invalid system: '.$_GET['url'];
            Logs::writeLog('Invalid', $msg);
            header('Location: /err/invalid-system');
            die();
        }
        
        //  Get the types of files that are saved for systems
        $fileTypes = $model->getFileTypes();
        $fileList = '<ul class="nav nav-tabs">';
        $fileData = '<div class="tab-content">';
        $optList  = '';
        $i = 0;
        foreach($fileTypes as $type)
        {
            $fileList .= '<li ';
            $fileList .= $i == 0 ? 'class="active">' : '>';
            $fileList .= '<a href="#'.str_replace(' ', '-', $type->description).'" data-toggle="tab">'.$type->description.'</a></li>';

            $fileData .= '<div id="'.str_replace(' ', '-', $type->description).'"class="tab-pane table-responsive ';
            $fileData .= $i == 0 ? 'active">' : 'fade">';
            $fileData .= '<table class="table table-striped ajax-table" data-load="'.str_replace(' ', '-', $type->description).'"><thead><tr><th>File</th><th>Added By</th><th>Date Added</th></tr></thead><tbody><tr><td colspan="4" class="text-center">No Files</td></tr></tbody></table></div>';

            $optList .= '<option value="'.str_replace(' ', '-', $type->description).'">'.$type->description.'</option>';

            $i++;
        }
        $fileList .= '</ul>';
        $fileData .= '</div>';
        
        //  Create view data
        $data['sysName'] = $sys;
        $data['fileList'] = $fileList;
        $data['fileData'] = $fileData;
        $data['optList']  = $optList;
        
        $this->template('techUser');
        $this->view('system.systemData', $data);
    }
    
    //  Ajax call to pull all files for a specifi system
    public function getSysFiles($sysName, $fileType)
    {
        $model = $this->model('systems');
        
        //  Verify that the system is valid
        if(!($sysID = $model->getSysID(str_replace('-', ' ', $sysName))))
        {
            $msg = 'User: '.$_SESSION['id'].' attempted to access an invalid system: '.$_GET['url'];
            Logs::writeLog('Invalid', $msg);
            header('Location: /err/invalid-system');
            die();
        }
        
        //  Pull the files from the database
        $files = $model->getSysFiles(str_replace('-', ' ', $sysName), str_replace('-', ' ', $fileType));
        
        //  Sort through files and arrange them
        if(empty($files))
        {
            $fileData = '<tr><td colspan="4" class="text-center">No Files</td></tr>';
        }
        else
        {
            $fileData = '';
            foreach($files as $file)
            {
                $author = Template::getUserName($file->user_id);
                
                $fileData .= '<tr><td><a href="/download/'.$file->file_id.'/'.$file->file_name.'" data-content="'.$file->description.'" data-toggle="popover">'.$file->name.'</a></td><td>'.$author.'</td><td>'.date('M j, Y', strtotime($file->added_on)).'</td></tr>';
            }
        }
        
        $this->render($fileData);
    }
    
    //  Ajax call to submit a new system file
    public function submitNewFile($sysName)
    {
        $model = $this->model('systems');
        $fileModel = $this->model('files');
        $success = false;
        
        //  Verify that the system is valid
        if(!($sysID = $model->getSysID(str_replace('-', ' ', $sysName))))
        {
            $msg = 'User: '.$_SESSION['id'].' attempted to access an invalid system: '.$_GET['url'];
            Logs::writeLog('Invalid', $msg);
            header('Location: /err/invalid-system');
            die();
        }
        
        $filePath = Config::getFile('uploadRoot').Config::getFile('sysPath').$model->getSysFolder($sysName);
        
        if(!empty($_FILES))
        {
            $fileModel->setFileLocation($filePath);
            $fileID = $fileModel->processFiles($_FILES, $_SESSION['id'], 'tech');
            $fileData = $_POST;
            $fileData['fileID'] = $fileID[0];
            $fileData['sysID'] = $model->getSysID(str_replace('-', ' ', $sysName));
            $fileData['user'] = $_SESSION['id'];
            
            $model->addSysFile($fileData, $_SESSION['id']);
            $success = true;
            
            //  Write a log file noting file was uploaded
            $msg = 'New file added for '.$sysName.'. File ID: '.$fileData['fileID'].' User: '.$fileData['user'].'.';
            Logs::writeLog('File-Upload', $msg);
            
            //  Notify users that the file was added
            $notifyMsg = 'New File Added For '.str_replace('-', ' ', $sysName);
            $notifyLnk = '/system/'.$model->getSysCategory($sysName).'/'.$sysName;
            Template::notifyAllUsers($notifyMsg, $notifyLnk);
        }
        
        $this->render($success);
    }
    
    //  Ajax call to load the form for a selected system
    public function loadSystemForm($sysName)
    {
        $model = $this->model('systems');
        
        $sysID = $model->getSysID($sysName);
        $table = $model->getSysTable($sysID);
        $cols  = $model->getCols($table);
        
        $form = '';
        foreach($cols as $col)
        {
            if($col->COLUMN_NAME != 'data_id' && $col->COLUMN_NAME != 'cust_id')
            {
                $form .= '<div class="form-group"><label for="'.$col->COLUMN_NAME.'">'.strtoupper(str_replace('_', ' ', $col->COLUMN_NAME)).'</label><input type="text" id="'.$col->COLUMN_NAME.'" name="'.$col->COLUMN_NAME.'" class="form-control" /></div>';
            }
        }
        
        $this->render($form);
    }
}
