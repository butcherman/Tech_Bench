<?php
/*
|   Forms controller allows users to upload common company forms such as Time Off Requests, or Incident Reports
*/

class Forms extends Controller
{
    //  Constructor sets the page security level and reroutes if user is not logged in
    public function __construct()
    {
        Security::setPageLevel('tech');
        if(!Security::doIBelong())
        {
            $_SESSION['returnURL'] = $_GET['url'];
            header('Location: /err/restricted');
            die();
        }
    }
    
    public function index()
    {
        $this->template('techUser');
        $this->view('forms/list_all_forms');
        $this->render();
    }
    
    public function newFormSubmit()
    {
        $model     = $this->model('companyForms');
        $fileModel = $this->model('files');
        
        $path = Config::getFile('uploadRoot').Config::getFile('formPath');
        $fileModel->setFileLocation($path);
        $fileID = $fileModel->processFiles($_FILES, $_SESSION['id'], 'tech');
        
        $model->addNewForm($_POST['name'], $fileID[0]);
        
        //  Create a notification for the dashboard
        $msg = 'New Company Form Loaded - '.$_POST['name'];
        $lnk = '/forms';
        Template::notifyAllUsers($msg, $lnk);
        
        //  Log the change in the log file
        $msg = 'New Company Form Loaded - '.$_POST['name'].' by ('.$_SESSION['id'].')'.Template::getUserName($_SESSION['id']);
        Logs::writeLog('Company-Forms', $msg);
        
        $this->render('success');
    }
    
    //  Ajax call to load company forms
    public function loadForms()
    {
        $model = $this->model('companyForms');
        $files = $model->getFiles();
        
        if(!$files)
        {
            $content = '<h4 class="text-center">No Files</h4>';
        }
        else
        {
            $i = 0;
            $r = 6;
            $content = '<div class="row top-buffer">';
            
            foreach($files as $file)
            {
                $fileData = new SplFileInfo($file->file_name);
                $ext      = $fileData->getExtension();
                
                $base = '/source/img/file_icons/';
                if(file_exists('../public/source/img/file_icons/'.$ext.'.png'))
                {
                    $src = '/source/img/file_icons/'.$ext.'.png';
                }
                else
                {
                    $src = '/source/img/file_icons/_blank.png';
                }
                
                if($i % $r == 0 && $i != 0)
                {
                    $content .= '</div><div class="row">';
                }
                
                $content .= '<div class="col-xs-6 col-md-2"><div class="thumbnail"><a href="/download/'.$file->file_id.'/'.$file->file_name.'"><img src="'.$src.'" alt="File" /><div class="caption text-center"><strong>'.$file->name.'</strong></div></div></div>';
                
                $i++;
            }
            
            $content .= '</div>';
        }
        
        $this->render($content);
    }
}
